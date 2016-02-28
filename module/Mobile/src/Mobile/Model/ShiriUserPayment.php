<?php
/**
 * Created by PhpStorm.
 * User: alois
 * Date: 9/13/2015
 * Time: 8:50 PM
 */

namespace Mobile\Model;


use Application\Entity\ClustersPayments;
use Application\Entity\FromNettcashServer;
use Application\Entity\NettcashPayments;
use Application\Entity\PackagePlans;
use Application\Entity\PackagePlansFigures;
use Application\Entity\UserPayments;
use Application\Entity\UserPolicies;
use Application\Entity\Users;
use Application\Model\Constants;
use Application\Model\Crypt;
use Application\Model\DoctrineInitialization;
use Application\Model\infobipSMSMessaging;
use Application\Model\PasswordCompatibilityLibrary;
use DOMDocument;
use SimpleXMLElement;

class ShiriUserPayment extends DoctrineInitialization
{


    const REFERENCEID = 'referenceid';

    const KEY = 'key';

    const API_USER = 'api_user';

    const PASSWORD = 'password';

    const USER_PHONE_NUMBER = 'user_phone_number';

    const AMOUNT = 'amount';

    const FIVE = 5;

    const PAYMENT_TRANSACTION_FAILED = 'Payment transaction failed';

    const PAYMENT_TRANSACTION_FAILED_WRONG_PHONE_NUMBER = 'Payment transaction failed, Wrong Phone Number';

    const PAYMENT_TRANSACTION_FAILED_WRONG_PHONE_NUMBER_OR_AMOUNT_0_NOT_SET = 'Payment transaction failed, Wrong Phone Number or amount = 0/not set';

    const AUTHENTICATION_FAILED_PLEASE_PROVIDE_CORRENT_API_ID_FOR_SECURE_AUTHENTICATION = 'Authentication failed, please provide corrent api id, for secure authentication';

    const FAILED_TO_PROCESS_YOUR_DATA_NO_DATA_WAS_SET = 'failed to process your data, no data was set';

    function __construct($service_locator)
    {
        parent::__construct($service_locator);
    }

    public function processPayment($request){
        $this->setEntityManager();
        $util = new Utils();
        $crypt = new Crypt(Constants::ENCRYPTION_KEY);
        if( $request != NULL)
        {
            $db = new DBUtils($this->service_locator);

            $amount_paid = $request[self::AMOUNT];
            $phone_number = $request[self::USER_PHONE_NUMBER];
            $password = $request[self::PASSWORD];
            $api_user_id = $request[self::API_USER];
            $dec_key = $request[self::KEY];
          //  $referenceid = $request[self::REFERENCEID];
            $fname = "";
            $sname = "";
            $plnumber = "";
            $current_amount ="";
            $transaction_id = $this->random_id(self::FIVE);//md5(uniqid());
            $gcm_reg_id = "";
            //  $crypt = new Crypt(ENCRYPTION_KEY);

            $api_user_id = htmlspecialchars($api_user_id);
//            $sql = "SELECT user_name, user_password_hash
//                        FROM admin_users
//                        WHERE user_name = '".$api_user_id."'";
            $query = $this->entity_manager->createQueryBuilder();
            $query->select(array('u'))
                ->from('Application\Entity\AdminUsers', 'u')
                ->where($query->expr()->orX(
                    $query->expr()->eq('u.userName', '?1')
                ))
                ->setParameter(1, $api_user_id);
            $query = $query->getQuery();
            $user_deps = $query->getResult();
            if($user_deps != null){

                $key = Constants::VERIFY_KEY;
                // $salt = '$2y$07$BCFvHUNcDnOPnUwwBzVlQH0piJtjXl.0t1XkA8pw9dMXTpOq';
                $salt = Constants::SALT_KEY;
                $hashed_password = crypt($key, $salt);
                $verify_password = "";
                $verify_user = "";
                $verify_key = $crypt->decrypt($dec_key);
//                while($results = mysql_fetch_array($raw_results)){
//                    $verify_password = $results['user_password_hash'];
//                    $verify_user = $results['user_name'];
//                }
                foreach($user_deps as $login){
                    //  $login = new AdminUsers();
                    $verify_password = $login->getUserPasswordHash();
                    $verify_user = $login->getUserName();
                }
                $verified = false;
                if (version_compare(PHP_VERSION, '5.5.0', '<')) {

                    $passwrd = new PasswordCompatibilityLibrary(1);
                    $verified = $passwrd->password_verify($verify_key, $verify_password);
                }else {
                    $verified = password_verify($verify_key, $verify_password);
                }
                if ($verified &&
                    crypt($key, $hashed_password) === $password) {


                    if(strlen($phone_number) <= Constants::PHONE_NUMBER_LENGTH && !empty($amount_paid) && $amount_paid !== 0){
                       // $phone_number = htmlspecialchars($phone_number);
                        $user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($phone_number);// "SELECT * FROM users WHERE phone_number = '".$phone_number."'";
//                        if ($user == null) {
//                            //no user
//                            return $util->return_results(Constants::ON_FAILURE_CONST);
//                        }
                        if($user != null){
                            //  $user = new Users();

                            //   $fname = $user->getFirstName();
                            //  $sname =  $user->getLastName();
                            $gcm_reg_id= $user->getGcmRegid();
                            //   $plnumber = $results['policy_number'];
                            //    $current_amount = $results['amount'];

                            date_default_timezone_set("UTC");
                            $now = new \DateTime();
                            $timestamp =  $now->getTimestamp();

                            $nettcash = new FromNettcashServer();
                            $nettcash->setUser($user);
                            $nettcash->setAmountPaid($amount_paid);
                            $nettcash->setTransactionId($transaction_id);
                            $nettcash->setDatePaid($timestamp);
                            $nettcash->setSendState(false);
                            $this->entity_manager->persist($nettcash);
                            $this->entity_manager->flush();
                            $resulting_id = $nettcash->getId();


                            if($resulting_id > 0){
                                $amount_paid = number_format($amount_paid, 2);
                                $res = $db->save_individual_client_messages(Constants::SHIRI_FUNERAL_PLAN_PAYMENT, Constants::YOU_HAVE_PAID .$amount_paid. Constants::FOR_YOUR_POLICY_PREMIUM
                                    , $phone_number);
                                if($gcm_reg_id === Constants::GCM_REG_ID_DEFAULT){

//                                  $NMessageObj = new NexmoMessage("9bea963b","5214fca6");
//                                  // $phone_number_r  = "+263773212212";
//                                  $result = $NMessageObj->sendText($phone_number,"Shiri",
//                                      "You have paid $".$amount_paid." for your policy account",null);
//
//                                  if (!empty($result)) {
//                                      $result = json_encode($result);
//                                  }
                                    $infobipSMSMessaging = new infobipSMSMessaging();
                                    //    $phone_number  = "+263783211562";
                                    $result =  $infobipSMSMessaging->sendmsg($phone_number,Constants::SHIRI_NAME, Constants::YOU_HAVE_PAID .$amount_paid. Constants::FOR_YOUR_POLICY_PREMIUM);

                                }else {
                                    $util->notifyPayments($gcm_reg_id, Constants::YOU_HAVE_PAID .$amount_paid. Constants::FOR_YOUR_POLICY_PREMIUM,$amount_paid);
                                }
                                $paymentTypeRecord =  $this->entity_manager->getRepository(Constants::ENTITY_PAYMENT_TYPES)->findOneById(Constants::NETTCASH);

                                $pending_payment =  $this->entity_manager->getRepository(Constants::ENTITY_PENDING_PAYMENTS)->findBy(array('user'=>$user,'paymentType'=>$paymentTypeRecord,'amount'=>$amount_paid),array('dateUploaded'=>'DESC'),1);
                                if($pending_payment!=null){
                                    $pending_payment->setCleared(true);
                                    $pending_payment->setDateCleared(round(microtime(true)*1000));
                                    $this->entity_manager->flush();
                                }



                                header('Content-Type: text/xml');
                                $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><entries/>');
                                $entries= $xml->addChild('entry');

                                $entries->addChild('result_code', Constants::PAYMENT_SUCCESS);
                                $entries->addChild('result_description', Constants::NETTCASH_PAYMENT_SUCCESSFUL);
                                $entries->addChild('transaction_id', $transaction_id);
                                if($pending_payment->includesJoiningFee()){
                                    //TODO do admin transaction type also
                                $this->scheduleClusterPayment($user->getReferer(),Constants::REFERRAL_TRANSACTION_TYPE,$entries);
                                }
                                $dom = new DOMDocument('1.0');
                                $dom->preserveWhiteSpace = false;
                                $dom->formatOutput = true;
                                $dom->loadXML($xml->asXML());
                                return $dom->saveXML();

                            }else{
                                return $util->onfailure(Constants::PAYMENT_FAILED, self::PAYMENT_TRANSACTION_FAILED);

                            }

                        }else{
//todo use constanst here
                            return $util->onfailure(Constants::PAYMENT_FAILED, self::PAYMENT_TRANSACTION_FAILED_WRONG_PHONE_NUMBER);
                        }
                    }else{

                        return  $util->onfailure(Constants::PAYMENT_FAILED, self::PAYMENT_TRANSACTION_FAILED_WRONG_PHONE_NUMBER_OR_AMOUNT_0_NOT_SET);
                    }
                }else{

                    return   $util->response(Constants::PAYMENT_AUTH_FAILED, self::AUTHENTICATION_FAILED_PLEASE_PROVIDE_CORRENT_API_ID_FOR_SECURE_AUTHENTICATION);
                }
            }else{

                return   $util->response(Constants::PAYMENT_AUTH_FAILED, self::AUTHENTICATION_FAILED_PLEASE_PROVIDE_CORRENT_API_ID_FOR_SECURE_AUTHENTICATION);
            }

        }else{
            return  $util->onfailure(Constants::PAYMENT_NO_DATA_SET, self::FAILED_TO_PROCESS_YOUR_DATA_NO_DATA_WAS_SET);
        }
    }

    function random_id($bytes) {
        $rand = mcrypt_create_iv($bytes, MCRYPT_DEV_URANDOM);
        return bin2hex($rand);
    }

    public function processTestPayment($request){

    $this->setEntityManager();
    $util = new Utils();
    $crypt = new Crypt(Constants::ENCRYPTION_KEY);
    if( $request != NULL)
    {
        $db = new DBUtils($this->service_locator);

        $amount_paid = $request[self::AMOUNT];
        $phone_number = $request[self::USER_PHONE_NUMBER];
        $password = $request[self::PASSWORD];
        $api_user_id = $request[self::API_USER];
        $dec_key = $request[self::KEY];
        //  $referenceid = $request[self::REFERENCEID];
        $fname = "";
        $sname = "";
        $plnumber = "";
        $current_amount ="";
        $transaction_id = $this->random_id(self::FIVE);//md5(uniqid());
        $gcm_reg_id = "";
        //  $crypt = new Crypt(ENCRYPTION_KEY);

        $api_user_id = htmlspecialchars($api_user_id);
//            $sql = "SELECT user_name, user_password_hash
//                        FROM admin_users
//                        WHERE user_name = '".$api_user_id."'";
        $query = $this->entity_manager->createQueryBuilder();
        $query->select(array('u'))
            ->from('Application\Entity\AdminUsers', 'u')
            ->where($query->expr()->orX(
                $query->expr()->eq('u.userName', '?1')
            ))
            ->setParameter(1, $api_user_id);
        $query = $query->getQuery();
        $user_deps = $query->getResult();
        if($user_deps != null){

            $key = Constants::VERIFY_KEY;
            // $salt = '$2y$07$BCFvHUNcDnOPnUwwBzVlQH0piJtjXl.0t1XkA8pw9dMXTpOq';
            $salt = Constants::SALT_KEY;
            $hashed_password = crypt($key, $salt);
            $verify_password = "";
            $verify_user = "";
            $verify_key = $crypt->decrypt($dec_key);
//                while($results = mysql_fetch_array($raw_results)){
//                    $verify_password = $results['user_password_hash'];
//                    $verify_user = $results['user_name'];
//                }
            foreach($user_deps as $login){
                //  $login = new AdminUsers();
                $verify_password = $login->getUserPasswordHash();
                $verify_user = $login->getUserName();
            }
            $verified = false;
            if (version_compare(PHP_VERSION, '5.5.0', '<')) {

                $passwrd = new PasswordCompatibilityLibrary(1);
                $verified = $passwrd->password_verify($verify_key, $verify_password);
            }else {
                $verified = password_verify($verify_key, $verify_password);
            }
            if ($verified &&
                crypt($key, $hashed_password) === $password) {


                if(strlen($phone_number) <= Constants::PHONE_NUMBER_LENGTH && !empty($amount_paid) && $amount_paid !== 0){
                    $phone_number = htmlspecialchars($phone_number);
                    $user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($phone_number);// "SELECT * FROM users WHERE phone_number = '".$phone_number."'";
//                        if ($user == null) {
//                            //no user
//                            return $util->return_results(Constants::ON_FAILURE_CONST);
//                        }
                    if($user != null){
                        //  $user = new Users();

                        //   $fname = $user->getFirstName();
                        //  $sname =  $user->getLastName();
                        $gcm_reg_id= $user->getGcmRegid();
                        //   $plnumber = $results['policy_number'];
                        //    $current_amount = $results['amount'];

                        date_default_timezone_set("UTC");
                        $now = new \DateTime();
                        $timestamp =  $now->getTimestamp();

//                        $nettcash = new FromNettcashServer();
//                        $nettcash->setUser($user);
//                        $nettcash->setAmountPaid($amount_paid);
//                        $nettcash->setTransactionId($transaction_id);
//                        $nettcash->setDatePaid($timestamp);
//                        $nettcash->setSendState(false);
//                        $this->entity_manager->persist($nettcash);
//                        $this->entity_manager->flush();
//                        $resulting_id = $nettcash->getId();
//
//
//                        if($resulting_id > 0){
//                            $res = $db->save_individual_client_messages(Constants::SHIRI_FUNERAL_PLAN_PAYMENT, Constants::YOU_HAVE_PAID .$amount_paid. Constants::FOR_YOUR_POLICY_ACCOUNT
//                                , $phone_number);
//                            if($gcm_reg_id === Constants::GCM_REG_ID_DEFAULT){
//
////                                  $NMessageObj = new NexmoMessage("9bea963b","5214fca6");
////                                  // $phone_number_r  = "+263773212212";
////                                  $result = $NMessageObj->sendText($phone_number,"Shiri",
////                                      "You have paid $".$amount_paid." for your policy account",null);
////
////                                  if (!empty($result)) {
////                                      $result = json_encode($result);
////                                  }
//                                $infobipSMSMessaging = new infobipSMSMessaging();
//                                //    $phone_number  = "+263783211562";
//                                $result =  $infobipSMSMessaging->sendmsg($phone_number,Constants::SHIRI_NAME, Constants::YOU_HAVE_PAID .$amount_paid. Constants::FOR_YOUR_POLICY_ACCOUNT);
//
//                            }else {
//                                $util->notifyPayments($gcm_reg_id, Constants::YOU_HAVE_PAID .$amount_paid. Constants::FOR_YOUR_POLICY_ACCOUNT,$amount_paid);
//                            }
//
//                            header('Content-Type: text/xml');
                        //todo add missing Php question mark key letter bellow
//                            $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"><entries/>');
//                            $entries= $xml->addChild('entry');
//
//                            $entries->addChild('result_code', Constants::PAYMENT_SUCCESS);
//                            $entries->addChild('result_description', Constants::NETTCASH_PAYMENT_SUCCESSFUL);
//                            $entries->addChild('transaction_id', $transaction_id);
//
//                            $dom = new DOMDocument('1.0');
//                            $dom->preserveWhiteSpace = false;
//                            $dom->formatOutput = true;
//                            $dom->loadXML($xml->asXML());
//                            return $dom->saveXML();
//
//                        }else{
//                            return $util->onfailure(Constants::PAYMENT_FAILED, self::PAYMENT_TRANSACTION_FAILED);
//
//                        }

                    }else{
//todo use constanst here
                        return $util->onfailure(Constants::PAYMENT_FAILED, self::PAYMENT_TRANSACTION_FAILED_WRONG_PHONE_NUMBER);
                    }
                }else{

                    return  $util->onfailure(Constants::PAYMENT_FAILED, self::PAYMENT_TRANSACTION_FAILED_WRONG_PHONE_NUMBER_OR_AMOUNT_0_NOT_SET);
                }
            }else{

                return   $util->response(Constants::PAYMENT_AUTH_FAILED, self::AUTHENTICATION_FAILED_PLEASE_PROVIDE_CORRENT_API_ID_FOR_SECURE_AUTHENTICATION);
            }
        }else{

            return   $util->response(Constants::PAYMENT_AUTH_FAILED, self::AUTHENTICATION_FAILED_PLEASE_PROVIDE_CORRENT_API_ID_FOR_SECURE_AUTHENTICATION);
        }

    }else{
        return  $util->onfailure(Constants::PAYMENT_NO_DATA_SET, self::FAILED_TO_PROCESS_YOUR_DATA_NO_DATA_WAS_SET);
    }
}

    public function savePayment($field_a){
        $this->setEntityManager();
        $util = new Utils();
        if ($field_a != NULL) {
            $request = json_decode($field_a,true);
            $phone_number = $request[Constants::PHONE_NUMBER];
            $timestamp = $request['paidAt'];
            $amount_paid = $request[self::AMOUNT];
            $referenceId = $request['referenceId'];


            if(strlen($phone_number) <= Constants::PHONE_NUMBER_LENGTH){
                $phone_number = htmlspecialchars($phone_number);

                $user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($phone_number);// "SELECT * FROM users WHERE phone_number = '".$phone_number."'";
                if ($user == null) {
                    //no user
                    return $util->return_results(Constants::ON_FAILURE_CONST);
                }
                $query = $this->entity_manager->createQueryBuilder();
                $query->select(array('p'))
                    ->from('Application\Entity\NettcashPayments', 'p')
                    // ->where('d.user_id = ?1')
                    ->where($query->expr()->orX(
                        $query->expr()->eq('p.user', '?1')
                    ))
                    ->andWhere($query->expr()->orX(
                        $query->expr()->eq('p.transactionId', '?2')
                    ))
                    ->setParameters(array(1 => $user, 2 => $referenceId))
                    ->orderBy('p.id', 'DESC')
                    ->setMaxResults(1);
                $query = $query->getQuery();
                $data_result = $query->getResult();
                if($data_result != null){
                    return $util->return_results(Constants::ON_SUCCESS_CONST);
                }else{
                    //                    date_default_timezone_set("UTC");
//                    $now = new \DateTime();
//                    $timestamp = $now->getTimestamp();

                    $nettcash = new NettcashPayments();
                    $nettcash->setUser($user);
                    $nettcash->setAmountPaid($amount_paid);
                    $nettcash->setTransactionId($referenceId);
                    $timestamp = $timestamp/1000;
                    $nettcash->setDatePaid($timestamp);
                    $this->entity_manager->persist($nettcash);
                    $this->entity_manager->flush();
                    //$resulting_id = $nettcash->getId();

                    return $util->return_results(Constants::ON_SUCCESS_CONST);

                }

            }else{
                //wrong phone number
                return $util->return_results(Constants::ON_FAILURE_CONST);
            }

        }else{
            //no data set
            return $util->return_results(Constants::ON_FAILURE_CONST);
        }
    }

    /**
     * @param $referredBy
     * @param $transactionType
     * @param $entries
     * @return array
     */
    public function scheduleClusterPayment($referredBy, $transactionType,&$entries)
    {
        $sql = 'SELECT * FROM referrals WHERE date_joined<>NULL AND referrer=:referrer_id AND paid_out=0';
        $conn = $this->entity_manager->getConnection('default');
        $stmt = $conn->prepare($sql);
        $stmt->bindValue("referrer_id", $referredBy->getUserId());
        $stmt->execute();

        if ($stmt->fetch() != null) {
            $record1 = null;
            $record2 = null;
            $record3 = null;
            $i = 0;
            while ($c = $stmt->fetch()) {
                $i++;
                if ($i == 1) $record1 = $c;
                else if ($i == 2) $record2 = $c;
                else if ($i == 3) {
                    $i = 0;
                    $record3 = $c;
                    $referredByRec1 = $this->entity_manager->getRepository(Constants::ENTITY_REFERRALS)->findOneById($record1['id']);
                    $referredByRec2 = $this->entity_manager->getRepository(Constants::ENTITY_REFERRALS)->findOneById($record2['id']);
                    $referredByRec3 = $this->entity_manager->getRepository(Constants::ENTITY_REFERRALS)->findOneById($record3['id']);

                    $referredByRec1->setPaidOut(true);
                    $referredByRec2->setPaidOut(true);
                    $referredByRec3->setPaidOut(true);

                    $paid_cluster = new ClustersPayments();
                    $paid_cluster->setLinkedIds($record1['id'] . '_' . $record2['id'] . '_' . $record3['id'])
                        ->setTransanctionStatus(Constants::CLUSTER_TRANSACTION_NOT_PAID)
                        ->setTransanctionType($transactionType)
                        ->setUserToPay($referredBy);
                    $this->entity_manager->persist($paid_cluster);
                    $this->entity_manager->flush();
                    $refId = Constants::randomCapsString(3) . $paid_cluster->getId();
                    $paid_cluster->setReferenceId($refId);
                    $this->entity_manager->flush();
                    //TODO send notification to user being paid that payment is ready

                    $entries->addChild('referee', $referredBy->getPhoneNumber());
                    $entries->addChild('referee_commission', 10.00);
                }

            }

        }
    }
}