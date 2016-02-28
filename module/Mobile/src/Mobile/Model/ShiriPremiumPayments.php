<?php
/**
 * Created by PhpStorm.
 * User: alois - mumeraalois@gmail.com
 * Date: 9/24/2015
 * Time: 5:31 PM
 */

namespace Mobile\Model;


use Application\Entity\FromNettcashServer;
use Application\Entity\SubscribedPackages;
use Application\Entity\UserDependents;
use Application\Entity\UserPayments;
use Application\Entity\UserRelationshipTypes;
use Application\Entity\Users;
use Application\Model\Constants;
use Application\Model\DoctrineInitialization;
use Application\Model\infobipSMSMessaging;

class ShiriPremiumPayments extends  DoctrineInitialization
{


    const FAILED_NO_DATA_SET_PLEASE_SET_DATA = "Failed no data set, please set data";

    const REFERENCE_ID_STR = '. REF_ID : ';

    function __construct($service_locator)
    {
        parent::__construct($service_locator);
    }

    public function paynow($json){

        $request = json_decode($json, true);

        if( $request != NULL)
        {
            $url = $request['url'];
            $mobile = $request['mobile'];
            $agentid = $request['agentid'];
            $password = $request['password'];
            $amount =$request['amount'];
            $method = $request['method'];
            $merchantid =$request['merchantid'];
            $pin =$request['pin'];
            $sms =$request['sms'];
            $billid = $request['billid'];

            $main_url = $url."agentid=".$agentid."&password=".$password.
                "&merchantid=".$merchantid."&method=".$method."&mobile=".$mobile."&pin=".$pin.
                "&sms=".$sms."&amount=".$amount."&billid=".$billid;

            $fields_string = null;
            $fields = null;
            $fields = array(
                "agentid" => $agentid."&",
                "password" => $password."&",
                "merchantid" => $merchantid."&",
                "method" => $method."&",
                "mobile" => $mobile."&",
                "pin" => $pin."&",
                "sms" => $sms."&",
                "amount" => $amount."&",
                "billid" => $billid);

            foreach ($fields as $key => $value) {
                $fields_string .= $key . "=" . $value; //. "&";
            }
            rtrim($fields_string, "& ");
            $http_request = $url . $fields_string;
            die($http_request);
            $ch = curl_init($http_request);
// Set some options - we are passing in a useragent too here

            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.52 Safari/537.17');
            curl_setopt($ch, CURLOPT_AUTOREFERER, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_VERBOSE, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
// Send the request & save response to $resp
            $xml_output = "<?xml version=\"1.0\"?>\n";
            try{
                $resp = curl_exec($ch);
                header("Content-type: text/xml");
                if(curl_errno($ch)){
                    echo 'Curl error: ' . curl_error($ch);
                }
            }catch (Exception $ex)
            {
                $xml_output .= "<entries>\n";
                $xml_output .= "\t<entry>\n";
                $xml_output .= "\t\t<result>" . $ex . "</result>\n";
                $xml_output .= "\t</entry>\n";
                $xml_output .= "</entries>";
                die($xml_output);
                // die("eror");
            }
            //$xml = simplexml_load_string($resp);
            $xml  = json_encode($resp);

            $xml_output .= "<entries>\n";
            $xml_output .= "\t<entry>\n";
            $xml_output .= "\t\t<result>" .  $resp . "</result>\n";
            $xml_output .= "\t</entry>\n";
            $xml_output .= "</entries>";

            curl_close($ch);
            die($resp);


        }else {

            $xml_output = "<?xml version=\"1.0\"?>\n";
            $xml_output .= "<entries>\n";
            $xml_output .= "\t<entry>\n";
            $xml_output .= "\t\t<result>" . self::FAILED_NO_DATA_SET_PLEASE_SET_DATA . "</result>\n";
            $xml_output .= "\t</entry>\n";
            $xml_output .= "</entries>";
            die($xml_output);

        }

    }

    public  function sendNotification($gcm_token_id,$phone_number,$amount, $message){
        $util = new Utils();
        $db = new DBUtils($this->service_locator);

        if(strcmp($gcm_token_id ,Constants::GCM_REG_ID_DEFAULT)== 0){

            $infobipSMSMessaging = new infobipSMSMessaging();
            //    $phone_number  = "+263783211562";
            $result =  $infobipSMSMessaging->sendmsg($phone_number,
                Constants::SHIRI_SMS_TITLE, $message);

        }else {
            $util->notifyPayments($gcm_token_id, $message,$amount);
        }

        $res = $db->save_individual_client_messages(Constants::SHIRI_FUNERAL_PLAN_PAYMENT,
            $message, $phone_number);

//        $res = $db->save_individual_client_messages(Constants::SHIRI_FUNERAL_PLAN_PAYMENT,
//            $user->getFirstName().' '.$user->getLastName()." have paid ".$amount_paid. Constants::FOR_YOUR_POLICY_ACCOUNT
//            , $frNumber);

    }

    const REFERENCEID = 'referenceId';
    public function insertPayment($field_a,$field_b,$field_c){

        $this->setEntityManager();
        $util = new Utils();
        $db = new DBUtils($this->service_locator);
        if( $field_a != NULL)
        {
            $request = json_decode($field_a, true);
            $num_of_items = $request['count'];
            $result = '<results>';

            if($num_of_items>0)
            {
               // $result.='<my_payment>';
                $paying_for_a_friend_notification=false;
                for($i=1;$i<=$num_of_items;$i++)
                {
                    $curr_item =  $request[$i];
                    $my_record = json_decode($curr_item, true);
                    $paidFor = $my_record['paidFor'];
                    $localId = $my_record[Constants::LOCAL_ID];
                    $datePaid = $my_record['paidAt'];
                    $totalAmountPaid = $my_record['totalAmountPaid'];
                    $datePaidfor = $my_record['datePaidfor'];
                    $phone_number = $my_record['phoneNumber'];
                    $referenceid = $my_record['referenceId'];
                    $paymentType = $my_record['paymentType'];
                    $amount_paidd =$my_record['amount'];
                    $frNumber =$my_record['frNumber'];
                    $isdep = $my_record['relation'];
                    $serverId =$my_record['serverId'];



                    $user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($phone_number);// "SELECT * FROM users WHERE phone_number = '".$phone_number."'";
                    if ($user == null) {
                        $result.='<error>USER_NOT_FOUND__'.$phone_number.'</error>';
                        continue;
                    }

                    $payment_type = $this->entity_manager->getRepository(Constants::ENTITY_PAYMENT_TYPES)->findOneById($paymentType);
                    if ($payment_type == null) {
                        $result.='<error>PAYMENT_TYPE_FOUND__'.$paymentType.'</error>';
                        continue;
                    }

                    $subscribed_package = $this->entity_manager->getRepository(Constants::ENTITY_SUBSCRIBED_PACKAGES)->findOneById($serverId);
                    if($subscribed_package == null){
                        $result.='<error>PACKAGE_WITH_ID_FOUND__'.$serverId.'</error>';
                        continue;
                    }
                    if (!$paying_for_a_friend_notification) {
                        $paying_for_a_friend_notification = true;
                        $timestamp = new \DateTime($datePaidfor);
                        $dt = $timestamp->format('Y-m-d 00:00:00');
                        $timestamp = new \DateTime($dt);
                        $last_payment = $this->entity_manager->getRepository(Constants::ENTITY_USER_PAYMENTS)->findBy(array('subscribedPackage' => $subscribed_package, 'monthPaidFor' => $timestamp));
                         if($last_payment == null){
                        if ($paidFor == Constants::FRIEND_PAYMENT) {
                            $fr_user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($frNumber);// "SELECT * FROM users WHERE phone_number = '".$phone_number."'";
                            if ($fr_user == null) {
                                $result .= '<error>PAYING_USER_FOUND__' . $frNumber . '</error>';
                                continue;
                            }
                            $amount_paid = number_format($totalAmountPaid, 2);
                            $f_gcm_reg_id = $fr_user->getGcmRegid();
                            $this->sendNotification($f_gcm_reg_id, $frNumber, $amount_paid
                                , $user->getFirstName() . ' ' . $user->getLastName() . ' has paid $' . $amount_paid . Constants::FOR_YOUR_POLICY_PREMIUM);
                            //sleep(2);
                         //   $result.='<notify>'.$serverId.'</notify>';
                            $gcm_reg_id = $user->getGcmRegid();
                            $this->sendNotification($gcm_reg_id, $phone_number, $amount_paid,
                                Constants::YOU_HAVE_PAID . $amount_paid . ' for ' . $fr_user->getFirstName() . ' ' . $fr_user->getLastName() . self::REFERENCE_ID_STR . $referenceid);
                        }
                    }

                    }
                    $result .= $this->savePaymentInDb($subscribed_package, $datePaid, $datePaidfor, $referenceid, $user, $localId, $isdep,$payment_type);
                }
               // $result.='</my_payment>';
            }
            $request = json_decode($field_c, true);
            $num_of_items = $request['count'];
            if($num_of_items>0)
            {
            //    $result.='<bus_payment>';
                for($i=1;$i<=$num_of_items;$i++)
                {
                    $curr_item =  $request[$i];
                    $my_record = json_decode($curr_item, true);
                    $paidFor = $my_record['paidFor'];
                    $localId = $my_record[Constants::LOCAL_ID];
                    $datePaid = $my_record['paidAt'];
                    $datePaidfor = $my_record['datePaidfor'];
                    $paymentType = $my_record['paymentType'];
                    $phone_number = $my_record['phoneNumber'];
                    $referenceid = $my_record['referenceId'];
                    $amount_paidd =$my_record['amount'];
                    $frNumber =$my_record['frNumber'];
                    $isdep = $my_record['relation'];
                    $serverId =$my_record['serverId'];

                    $user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($phone_number);// "SELECT * FROM users WHERE phone_number = '".$phone_number."'";
                    if ($user == null) {
                        $result.='<error>USER_NOT_FOUND__'.$phone_number.'</error>';
                        continue;
                    }
                    $payment_type = $this->entity_manager->getRepository(Constants::ENTITY_PAYMENT_TYPES)->findOneById($paymentType);
                    if ($payment_type == null) {
                        $result.='<error>PAYMENT_TYPE_FOUND__'.$paymentType.'</error>';
                        continue;
                    }

                    $subscribed_package = $this->entity_manager->getRepository(Constants::ENTITY_SUBSCRIBED_PACKAGES)->findOneById($serverId);
                    if($subscribed_package == null){
                        $result.='<error>PACKAGE_WITH_ID_FOUND__'.$serverId.'</error>';
                        continue;
                    }
                    $result .= $this->savePaymentInDb($subscribed_package, $datePaid, $datePaidfor, $referenceid, $user, $localId, $isdep,$payment_type);

                }
               // $result.='</bus_payment>';

            }
            $request = json_decode($field_b, true);
            $num_of_items = $request['dependantsCount'];
            if($num_of_items>0)
            {
             //   $result.='<dependants_payment count="'.$num_of_items.'">';
                for($i=1;$i<=$num_of_items;$i++)
                {
                    $curr_item =  $request[$i];
                    $my_record = json_decode($curr_item, true);
                    $num_of_items2 = $my_record['count'];
                    if($num_of_items2>0)
                    {
                       // $result.='<dependant>';
                        for($j=1;$j<=$num_of_items2;$j++)
                        {
                            $my_curr_item =  $my_record[$j];
                            $my_record1 = json_decode($my_curr_item, true);
                            $paidFor = $my_record1['paidFor'];
                            $localId = $my_record1[Constants::LOCAL_ID];
                            $datePaid = $my_record1['paidAt'];
                            $datePaidfor = $my_record1['datePaidfor'];
                            $paymentType = $my_record1['paymentType'];
                            $phone_number = $my_record1['phoneNumber'];
                            $referenceid = $my_record1['referenceId'];
                            $amount_paidd =$my_record1['amount'];
                            $frNumber =$my_record1['frNumber'];
                            $isdep = $my_record1['relation'];
                            $serverId =$my_record1['serverId'];

                            $user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($phone_number);// "SELECT * FROM users WHERE phone_number = '".$phone_number."'";
                            if ($user == null) {
                                $result.='<error>USER_NOT_FOUND__'.$phone_number.'</error>';
                                continue;
                            }
                            $payment_type = $this->entity_manager->getRepository(Constants::ENTITY_PAYMENT_TYPES)->findOneById($paymentType);
                            if ($payment_type == null) {
                                $result.='<error>PAYMENT_TYPE_FOUND__'.$paymentType.'</error>';
                                continue;
                            }

                            $subscribed_package = $this->entity_manager->getRepository(Constants::ENTITY_SUBSCRIBED_PACKAGES)->findOneById($serverId);
                            if($subscribed_package == null){
                                $result.='<error>PACKAGE_WITH_ID_FOUND__'.$serverId.'</error>';
                                continue;
                            }
                            if ($subscribed_package->getIsDependent()) {
                                $dep = $subscribed_package->getDependent();
                                //  $dep = new UserDependents();
                                $rel = $dep->getRelationType();
                                // $rel =  new UserRelationshipTypes();
                                if ($rel->getId() < Constants::IMMEDIATE_FAMILY) {

                                    $result.='<status for = "'.$datePaidfor.'">'.$localId.'</status>';//$subscribed_package->getId()
                                    continue;
                                }

                            }

                          //  $result.='<alreadyPaid>'.$localId.'</alreadyPaid>';
                            $result .=  $this->savePaymentInDb($subscribed_package, $datePaid, $datePaidfor, $referenceid, $user, $localId, $isdep,$payment_type);

                        }
                      //  $result.='</dependant>';

                    }

                } //$result.='</dependants_payment>';

            }

            $result .= '</results>';
            return $result;


        }else{
            //no data set
            return $util->return_results(Constants::ON_FAILURE_CONST);
        }




    }

    /**
     * @param $subscribed_package
     * @param $datePaid
     * @param $datePaidfor
     * @param $referenceid
     * @param $user
     * @param $localId
     * @param $isdep
     * @return string
     */
    public function savePaymentInDb($subscribed_package, $datePaid, $datePaidfor, $referenceid, $user, $localId, $isdep,$payment_type)
    {

        $last_payment = $this->entity_manager->getRepository(Constants::ENTITY_USER_PAYMENTS)->findBy(array('subscribedPackage' => $subscribed_package), array('monthPaidFor' => 'DESC'), 1);

        $timestamp = new \DateTime($datePaidfor);
        $dt = $timestamp->format('Y-m-d 00:00:00');
        $timestamp = new \DateTime($dt);
        date_default_timezone_set('UTC');
        $date = new \DateTime("now");
        $result = $date->format('Y-m-01 00:00:00');
        $date = new \DateTime($result);
        if ($last_payment == null) {
            $subscribed_package->setDateActivated($date);
            $this->entity_manager->flush();

        } else {
               $dt = $subscribed_package->getDateActivated();
               if ($date < $dt) {
                   $subscribed_package->setDateActivated($date);
                   $this->entity_manager->flush();
               }

        }

        $last_payment = $this->entity_manager->getRepository(Constants::ENTITY_USER_PAYMENTS)->findBy(array('subscribedPackage' => $subscribed_package,'monthPaidFor'=>$timestamp));


//        $res = '<info>SERVER_ID_' . $serverId . '_DATE_PAID_FOR_' . $datePaidfor . '_IS_DEP_' . $isdep . '_POLICY_ACTIVATED_' . date_format($subscribed_package->getDateActivated(), 'Y-m-d 00:00:00').'</info>';
       $res="";

        if($last_payment==null)
       {
           $userPayment = new UserPayments();
           $userPayment->setSubscribedPackage($subscribed_package);
           $dt_paid = new \DateTime($datePaid);
           $userPayment->setDatePaid($dt_paid);
           $userPayment->setMonthPaidFor($timestamp);
           $userPayment->setExternalRef($referenceid);
           $userPayment->setPayee($user);
           $userPayment->setSendState(false);
           $userPayment->setPaymentType($payment_type);
           $this->entity_manager->persist($userPayment);
           $this->entity_manager->flush();
           $res.='<status for = "'.$datePaidfor.'">'. $localId.'</status>';//$subscribed_package->getId()
       }else  $res.='<alreadyPaid for = "'.$datePaidfor.'">'.$localId.'</alreadyPaid>';//$subscribed_package->getId()
        return $res;
    }

}