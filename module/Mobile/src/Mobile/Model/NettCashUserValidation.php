<?php
/**
 * Created by PhpStorm.
 * User: alois
 * Date: 9/13/2015
 * Time: 8:50 PM
 */

namespace Mobile\Model;


use Application\Entity\AdminUpdates;
use Application\Entity\AdminUsers;
use Application\Entity\UserPayments;
use Application\Entity\Users;
use Application\Model\Constants;
use Application\Model\Crypt;
use Application\Model\DoctrineInitialization;
use Application\Model\PasswordCompatibilityLibrary;
use DOMDocument;
use SimpleXMLElement;

class NettCashUserValidation extends DoctrineInitialization
{

    const FAILED_TO_PROCESS_YOUR_DATA_NO_DATA_WAS_SET = 'failed to process your data, no data was set';
    const AUTHENTICATION_FAILED_PLEASE_PROVIDE_CORRENT_API_ID_FOR_SECURE_AUTHENTICATION = 'Authentication failed, please provide corrent api id, for secure authentication';

    const THE_ACCOUNT_REQUESTED_DOES_NOT_EXISTS = 'The Account Requested does not exists';


    function __construct($service_locator)
    {
        parent::__construct($service_locator);
    }

    public function validateUser($request){
        $this->setEntityManager();
        $util = new Utils();
        $crypt = new Crypt(Constants::ENCRYPTION_KEY);
        if( $request != NULL)
        {
            $phone_number = $request['user_phone_number'];
            $amount = $request['amount'];
            //  $policy_number = $request['policy_number'];
            $password = $request['password'];
            $api_user_id = $request['api_user'];
            $dec_key = $request['key'];
            $api_user_id = htmlspecialchars($api_user_id);
//            $result = $this->entity_manager->getRepository(Constants::ENTITY_ADMINUSERS)->findOneByUserName($api_user_id);// "SELECT * FROM users WHERE phone_number = '".$phone_number."'";
////                $result = mysql_query($sql) or die(mysql_error());
//
////            if ($result != null) {
////                return  $util->onfailure('here', self::FAILED_TO_PROCESS_YOUR_DATA_NO_DATA_WAS_SET);
////            }else{
////                return  $util->onfailure(Constants::PAYMENT_NO_DATA_SET, self::FAILED_TO_PROCESS_YOUR_DATA_NO_DATA_WAS_SET);
////            }
            $query = $this->entity_manager->createQueryBuilder();
            $query->select(array('u'))
                ->from('Application\Entity\AdminUsers', 'u')
                ->where($query->expr()->orX(
                    $query->expr()->eq('u.userName', '?1')
                ))
                ->setParameter(1, $api_user_id);
            $query = $query->getQuery();
            $user_deps = $query->getResult();
            if ($user_deps != null) {
                $key = "14Netdla15";
                //  $hashed_password = crypt($key);
                $salt = Constants::SALT_KEY;
                $hashed_password = crypt($key, $salt);
                $verify_password = "";
                $verify_user = "";
                $verify_key = $crypt->decrypt($dec_key);//mc_decrypt($dec_key, ENCRYPTION_KEY) ;
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
                if ($verified && crypt($key, $hashed_password) === $password) {


                    if(strlen($phone_number) <= Constants::PHONE_NUMBER_LENGTH){
                        $phone_number = htmlspecialchars($phone_number);

                        // $sql = "SELECT * FROM users WHERE phone_number = '".$phone_number."'";
                        $user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($phone_number);// "SELECT * FROM users WHERE phone_number = '".$phone_number."'";

                        //    $raw_results = mysql_query($sql) or die(mysql_error());

                        if($user != null){
                            //   while($results = mysql_fetch_array($raw_results)){

                            header('Content-Type: text/xml');
                            $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><entries/>');
                            $entries= $xml->addChild('entry');
                            $query = $this->entity_manager->getRepository(Constants::ENTITY_USER_PAYMENTS)->createQueryBuilder('n')
                                ->where('n.payee = :p_id')
                                ->setParameter('p_id', $user)
                                ->orderBy('n.id', 'DESC')
                                ->setMaxResults(1)
                                ->getQuery();
                            $user_payments = $query->getResult();
                            $date_paid ='';
                            if($user_payments != null && is_array($user_payments)) {
                                // $user_payments = new UserPayments();
                                foreach ($user_payments as $payment){
                                    $date_paid = $payment->getDatePaid();
                                }
//                                $due_Date= (int)$date_paid;
//                                // $due_Date = $due_Date/1000;
//                                $date = new \DateTime();
//                                $date = $date->setTimestamp($due_Date);
                                $date_paid = $date_paid->modify("last day of next month");
                                $date = $date_paid->format('Y-m-d');
                            }else{
                                $date_paid = $user->getCreatedAt();
                                $due_Date= $date_paid;
                                $due_Date = $due_Date/1000;
                                $date = new \DateTime();
                                $date = $date->setTimestamp($due_Date);
                                $date = $date->format('Y-m-d');
                                //  $date = date('Y-m-d', strtotime('+1 month', strtotime($date)));
                                $date = new \DateTime($date);
                                $date = $date->modify("last day of next month");
                                $date = $date->format("Y-m-d");
                            }

                            //  $user = new Users();


                            $entries->addChild('result_code', Constants::PAYMENT_SUCCESS);
                            $entries->addChild('result_description', Constants::ACCOUNT_DETAILS_PROVIDED_ARE_VALID);
                            $entries->addChild('user_name',   $user->getFirstName()." ".$user->getLastName()  );
                            $entries->addChild('next_due',  $date);

                            $query = $this->entity_manager->createQueryBuilder();
                            $query->select(array('pack'))
                                ->from('Application\Entity\SubscribedPackages', 'pack')
                                ->where($query->expr()->orX(
                                    $query->expr()->eq('pack.user', '?1')
                                ))
                                ->andWhere($query->expr()->orX(
                                    $query->expr()->eq('pack.isDependent', '?2')
                                ))
                                ->setParameters(array(1=> $user,2 =>false))
                                ->orderBy('pack.id', 'ASC')
                                ->setMaxResults(1);
                            $query = $query->getQuery();
                            $user_data = $query->getResult();
                            if ($user_data != null) {
//$count = 0;
                                foreach ($user_data as $package) {
                                    $plan = $package->getPackagePlan();
                                    $plan_figures = $this->entity_manager->getRepository(Constants::ENTITY_PACKAGEPLANFIGURES)->findOneByPackagePlan($plan);
//                                    //  return $this->return_results('test');
//                                    if ($plan_figures == null) {
//                                        //plan figures not set
//                                        return $this->return_results(Constants::ON_FAILURE_CONST);
//                                    }
                                    $due_amount = number_format($plan_figures->getAmount(),2);
                                    $entries->addChild('amount', $due_amount);
                                }

                            }


                            $dom = new DOMDocument('1.0');
                            $dom->preserveWhiteSpace = false;
                            $dom->formatOutput = true;
                            $dom->loadXML($xml->asXML());
                            echo $dom->saveXML();
                            //   }
                        }else{

                            return $this->response(Constants::PAYMENT_FAILED, self::THE_ACCOUNT_REQUESTED_DOES_NOT_EXISTS);
                        }


                    }else{
                        return $this->response(Constants::PAYMENT_WRONG_PHONENUMBER, Constants::FAILED_TO_PROCESS_YOUR_DATA_WRONG_PHONE_NUMBER);

                    }


                }else{
//                    header('Content-Type: text/xml');
//                    $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"><entries/>');
//                    $entries= $xml->addChild('entry');
//                    $entries->addChild('result_code', Constants::PAYMENT_NO_DATA_SET);
//                    $dom = new DOMDocument('1.0');
//                    $dom->preserveWhiteSpace = false;
//                    $dom->formatOutput = true;
//                    $dom->loadXML($xml->asXML());
//                    return $dom->saveXML();
//
                    return   $util->response(Constants::PAYMENT_AUTH_FAILED, self::AUTHENTICATION_FAILED_PLEASE_PROVIDE_CORRENT_API_ID_FOR_SECURE_AUTHENTICATION);
                }




            }else{
                return $this->response(Constants::PAYMENT_NO_DATA_SET, self::AUTHENTICATION_FAILED_PLEASE_PROVIDE_CORRENT_API_ID_FOR_SECURE_AUTHENTICATION);
            }
        }else{
            return  $util->onfailure(Constants::PAYMENT_NO_DATA_SET, self::FAILED_TO_PROCESS_YOUR_DATA_NO_DATA_WAS_SET);
        }
    }

    function response($result_code,$result_description){
        header('Content-Type: text/xml');
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><entries/>');
        $entries= $xml->addChild('entry');
        $entries->addChild('result_code', $result_code);
        $entries->addChild('result_description', $result_description);
        $entries->addChild('user_name', "");
        $entries->addChild('amount', "");
        $entries->addChild('next_due', "");

        $dom = new DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());
        return $dom->saveXML();
    }


    function mc_decrypt($decrypt, $key){
        $decrypt = explode('|', $decrypt.'|');
        $decoded = base64_decode($decrypt[0]);
        $iv = base64_decode($decrypt[1]);
        if(strlen($iv)!==mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC)){ return false; }
        $key = pack('H*', $key);
        $decrypted = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $decoded, MCRYPT_MODE_CBC, $iv));
        $mac = substr($decrypted, -64);
        $decrypted = substr($decrypted, 0, -64);
        $calcmac = hash_hmac('sha256', $decrypted, substr(bin2hex($key), -32));
        if($calcmac!==$mac){ return false; }
        $decrypted = unserialize($decrypted);
        return $decrypted;
    }

    public function validateUserTest($request)
    {

        $this->setEntityManager();
        $util = new Utils();
        $crypt = new Crypt(Constants::ENCRYPTION_KEY);
        if( $request != NULL)
        {
            $phone_number = $request['user_phone_number'];
            $amount = $request['amount'];
            //  $policy_number = $request['policy_number'];
            $password = $request['password'];
            $api_user_id = $request['api_user'];
            $dec_key = $request['key'];
            $api_user_id = htmlspecialchars($api_user_id);
//            $result = $this->entity_manager->getRepository(Constants::ENTITY_ADMINUSERS)->findOneByUserName($api_user_id);// "SELECT * FROM users WHERE phone_number = '".$phone_number."'";
////                $result = mysql_query($sql) or die(mysql_error());
//
////            if ($result != null) {
////                return  $util->onfailure('here', self::FAILED_TO_PROCESS_YOUR_DATA_NO_DATA_WAS_SET);
////            }else{
////                return  $util->onfailure(Constants::PAYMENT_NO_DATA_SET, self::FAILED_TO_PROCESS_YOUR_DATA_NO_DATA_WAS_SET);
////            }
            $query = $this->entity_manager->createQueryBuilder();
            $query->select(array('u'))
                ->from('Application\Entity\AdminUsers', 'u')
                ->where($query->expr()->orX(
                    $query->expr()->eq('u.userName', '?1')
                ))
                ->setParameter(1, $api_user_id);
            $query = $query->getQuery();
            $user_deps = $query->getResult();
            if ($user_deps != null) {
                $key = "14Netdla15";
                //  $hashed_password = crypt($key);
                $salt = Constants::SALT_KEY;
                $hashed_password = crypt($key, $salt);
                $verify_password = "";
                $verify_user = "";
                $verify_key = $crypt->decrypt($dec_key);//mc_decrypt($dec_key, ENCRYPTION_KEY) ;
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
                if ($verified && crypt($key, $hashed_password) === $password) {


                    if(strlen($phone_number) <= Constants::PHONE_NUMBER_LENGTH){
                        $phone_number = htmlspecialchars($phone_number);

                        // $sql = "SELECT * FROM users WHERE phone_number = '".$phone_number."'";
                        $user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($phone_number);// "SELECT * FROM users WHERE phone_number = '".$phone_number."'";

                        //    $raw_results = mysql_query($sql) or die(mysql_error());

                        if($user != null){
                            //   while($results = mysql_fetch_array($raw_results)){

                            header('Content-Type: text/xml');
                            $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><entries/>');
                            $entries= $xml->addChild('entry');
                            $query = $this->entity_manager->getRepository(Constants::ENTITY_USER_PAYMENTS)->createQueryBuilder('n')
                                ->where('n.payee = :p_id')
                                ->setParameter('p_id', $user)
                                ->orderBy('n.id', 'DESC')
                                ->setMaxResults(1)
                                ->getQuery();
                            $user_payments = $query->getResult();
                            $date_paid ='';
                            if($user_payments != null && is_array($user_payments)) {
                                // $user_payments = new UserPayments();
                                foreach ($user_payments as $payment){
                                    $date_paid = $payment->getDatePaid();
                                }
//                                $due_Date= (int)$date_paid;
//                                // $due_Date = $due_Date/1000;
//                                $date = new \DateTime();
//                                $date = $date->setTimestamp($due_Date);
                                $date_paid = $date_paid->modify("last day of next month");
                                $date = $date_paid->format('Y-m-d');
                            }else{
                                $date_paid = $user->getCreatedAt();
                                $due_Date= (int)$date_paid;
                                $due_Date = $due_Date/1000;
                                $date = new \DateTime();
                                $date = $date->setTimestamp($due_Date);
                                $date = $date->format('Y-m-d');
                                //  $date = date('Y-m-d', strtotime('+1 month', strtotime($date)));
                                $date = new \DateTime($date);
                                $date = $date->modify("last day of next month");
                                $date = $date->format("Y-m-d");
                            }

                            //  $user = new Users();


                            $entries->addChild('result_code', Constants::PAYMENT_SUCCESS);
                            $entries->addChild('result_description', Constants::ACCOUNT_DETAILS_PROVIDED_ARE_VALID);
                            $entries->addChild('user_name',   $user->getFirstName()." ".$user->getLastName()  );
                            $entries->addChild('next_due',  $date);

                            $query = $this->entity_manager->createQueryBuilder();
                            $query->select(array('pack'))
                                ->from('Application\Entity\SubscribedPackages', 'pack')
                                ->where($query->expr()->orX(
                                    $query->expr()->eq('pack.user', '?1')
                                ))
                                ->andWhere($query->expr()->orX(
                                    $query->expr()->eq('pack.isDependent', '?2')
                                ))
                                ->setParameters(array(1=> $user,2 =>false))
                                ->orderBy('pack.id', 'ASC')
                                ->setMaxResults(1);
                            $query = $query->getQuery();
                            $user_data = $query->getResult();
                            if ($user_data != null) {
//$count = 0;
                                foreach ($user_data as $package) {
                                    $plan = $package->getPackagePlan();
                                    $plan_figures = $this->entity_manager->getRepository(Constants::ENTITY_PACKAGEPLANFIGURES)->findOneByPackagePlan($plan);
//                                    //  return $this->return_results('test');
//                                    if ($plan_figures == null) {
//                                        //plan figures not set
//                                        return $this->return_results(Constants::ON_FAILURE_CONST);
//                                    }
                                    $due_amount = number_format($plan_figures->getAmount(),2);
                                    $entries->addChild('amount', $due_amount);
                                }

                            }


                            $dom = new DOMDocument('1.0');
                            $dom->preserveWhiteSpace = false;
                            $dom->formatOutput = true;
                            $dom->loadXML($xml->asXML());
                            echo $dom->saveXML();
                            //   }
                        }else{

                            return $this->response(Constants::PAYMENT_FAILED, self::THE_ACCOUNT_REQUESTED_DOES_NOT_EXISTS);
                        }


                    }else{
                        return $this->response(Constants::PAYMENT_WRONG_PHONENUMBER, Constants::FAILED_TO_PROCESS_YOUR_DATA_WRONG_PHONE_NUMBER);

                    }


                }else{
                    return   $util->response(Constants::PAYMENT_AUTH_FAILED, self::AUTHENTICATION_FAILED_PLEASE_PROVIDE_CORRENT_API_ID_FOR_SECURE_AUTHENTICATION);
                }


            }else{
                return $this->response(Constants::PAYMENT_NO_DATA_SET, self::AUTHENTICATION_FAILED_PLEASE_PROVIDE_CORRENT_API_ID_FOR_SECURE_AUTHENTICATION);
            }
        }else{
            return  $util->onfailure(Constants::PAYMENT_NO_DATA_SET, self::FAILED_TO_PROCESS_YOUR_DATA_NO_DATA_WAS_SET);
        }
    }


}