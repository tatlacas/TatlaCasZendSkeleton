<?php
/**
 * Created by PhpStorm.
 * User: alois - mumeraalois@gmail.com
 * Date: 9/24/2015
 * Time: 5:31 PM
 */

namespace Mobile\Model;


use Application\Entity\Users;
use Application\Model\Constants;
use Application\Model\DoctrineInitialization;
use Application\Model\infobipSMSMessaging;

class FriendsPayments extends  DoctrineInitialization
{

    const SHIRI_POLICY_PAYMENTS = "Shiri policy payments";

    const FOR_YOUR_POLICY_ACCOUNT = " for your policy account";

    const HAVE_PAID = " have paid ";

    const FOR_STR = " for ";

    const YOU_HAVE_PAID = "You have paid ";

    const POLICY_ACCOUNT = " policy account";

    function __construct($service_locator)
    {
        parent::__construct($service_locator);
    }

    public function friendPayment($json){
        $request = json_decode($json, true);

        if( $request != NULL)
        {
            $request = json_decode($json, true);
            $phone_number = $request['phone_number'];
            $f_phone_number = $request['f_phone_number'];
            $amount= $request['amount'];
            $ref_number = $request['ref_number'];
            $gcm_reg_id = "";
            $f_gcm_reg_id = "";
            $db = new DBUtils($this->service_locator);

            if(strlen($phone_number) <= Constants::PHONE_NUMBER_LENGTH && strlen($f_phone_number) <= Constants::PHONE_NUMBER_LENGTH){
                $phone_number = htmlspecialchars($phone_number);
                $f_phone_number = htmlspecialchars($f_phone_number);

                $user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($phone_number);// "SELECT * FROM users WHERE phone_number = '".$phone_number."'";
//                $result = mysql_query($sql) or die(mysql_error());

                if ($user != null) {
                   // $user = new Users();
                    $user_name = "";
                    $user_sname = "";
                    $space = " ";

                        $user_name = $user->getFirstName();//$results['first_name'];
                        $user_sname = $user->getLastName();//$results['last_name'];
                        $gcm_reg_id = $user->getGcmRegid();//$results['gcm_regid'];


                    $fr_user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($f_phone_number);// "SELECT * FROM users WHERE phone_number = '".$phone_number."'";
//                $result = mysql_query($sql) or die(mysql_error());

                    if ($fr_user != null) {

                        $f_user_name = "";
                        $f_user_sname = "";
                        $f_user_name = $fr_user->getFirstName();//$results['first_name'];
                        $f_user_sname = $fr_user->getLastName();//$results['last_name'];
                        $f_gcm_reg_id = $fr_user->getGcmRegid();//$results['gcm_regid'];

                        date_default_timezone_set("UTC");
                        $now = new \DateTime();
                        $timestamp =  $now->getTimestamp();

                        $sql="INSERT INTO friends_payments (first_name,friend_name ,phone_number, date_time, f_number,ref_number,
                                                       amount_paid)
VALUES ('$user_name.$space.$user_sname ','$f_user_name.$space.$f_user_sname '
       ,'$phone_number', $timestamp,'$f_phone_number', '$ref_number','$amount')";

                        $result = mysql_query($sql) or die(mysql_error());
                        $resulting_id = mysql_insert_id();
                        if($result){

                            if($gcm_reg_id === Constants::GCM_REG_ID_DEFAULT){
                                $infobipSMSMessaging = new infobipSMSMessaging();
                                //    $phone_number  = "+263783211562";
                                //todo process sms response
                                $result = $infobipSMSMessaging->sendmsg($phone_number, self::SHIRI_POLICY_PAYMENTS,
                                    self::YOU_HAVE_PAID . $amount . self::FOR_STR . $f_user_name . $space . $f_user_sname . self::POLICY_ACCOUNT );

                            }else {
                                $db->send_notification($gcm_reg_id, self::SHIRI_POLICY_PAYMENTS,
                                    self::YOU_HAVE_PAID . $amount . self::FOR_STR . $f_user_name . $space . $f_user_sname . self::POLICY_ACCOUNT);
                            }

                            sleep(5);

                            if($f_gcm_reg_id === Constants::GCM_REG_ID_DEFAULT){
                                $infobipSMSMessaging = new infobipSMSMessaging();
                                //    $phone_number  = "+263783211562";
                                //todo process sms response
                                $result = $infobipSMSMessaging->sendmsg($f_phone_number, self::SHIRI_POLICY_PAYMENTS,
                                    $user_name . $space . $user_sname . self::HAVE_PAID . $amount . self::FOR_YOUR_POLICY_ACCOUNT);

                            }else {
                                $db->send_notification($f_gcm_reg_id, self::SHIRI_POLICY_PAYMENTS,
                                    $user_name . $space . $user_sname . self::HAVE_PAID . $amount . self::FOR_YOUR_POLICY_ACCOUNT);
                            }

                            return $this->return_results(Constants::ON_SUCCESS_CONST);

                        }else{

                            return $this-> return_results(Constants::ON_FAILURE_CONST);

                        }
                    }else{
                          //no friend's account
                        return $this-> return_results(Constants::ON_FAILURE_CONST);
                    }

                }else{
                     //no account
                    return $this->return_results(Constants::ON_FAILURE_CONST);
                }


            }else{

                return $this->return_results(Constants::ON_FAILURE_CONST);
            }




        }else {
            return $this->return_results(Constants::FAILED_FIELD_REQUIRED);
        }
    }

    function return_results($value){
        $xml_output = "<?xml version=\"1.0\"?>\n";
        $xml_output .= "<entries>\n";
        $xml_output .= "\t<entry>\n";
        $xml_output .= "\t\t<result>" . $value. "</result>\n";
        $xml_output .= "\t</entry>\n";
        $xml_output .= "</entries>";
        return ($xml_output);
    }

//    function notifyPayments($gcm_reg_id, $message){
//        $gcm_push = new gcm_connect();
//        $registatoin_ids = array($gcm_reg_id);
//        $pushMessage = array("shiri_message" => $message ,
//            "title" => "Shiri policy payments");
//
//        $gcm_result = $gcm_push->gcm_send_message($registatoin_ids, $pushMessage);
//    }

}