<?php
/**
 * Created by PhpStorm.
 * User: alois - mumeraalois@gmail.com
 * Date: 10/20/2015
 * Time: 1:32 PM
 */

namespace Website\Model;


use Application\Entity\AdminUsers;
use Application\Entity\Messages;
use Application\Entity\Users;
use Application\Model\Constants;
use Application\Model\DoctrineInitialization;
use Application\Model\PasswordCompatibilityLibrary;
use Mobile\Model\GCMConnect;

class AdminRegistration extends  DoctrineInitialization
{
    // public $messages = array();
    public $messages = array();

    const SHIRI_NEWS = "Shiri News";

    function __construct($service_locator)
    {
        parent::__construct($service_locator);
    }

    public function  registerAdminNewUser($userName,$email,$user_password_new,$user_password_repeat){
        $result_state = Constants::ERROR;
        if (empty($userName)) {
            $this->messages[] = Constants::EMPTY_USERNAME;
        } elseif (empty($user_password_new) || empty($user_password_repeat)) {
            $this->messages[] = Constants::EMPTY_PASSWORD;
        } elseif (strcmp($user_password_new , $user_password_repeat) !== 0) {
            $this->messages[] = Constants::PASSWORD_AND_PASSWORD_REPEAT_ARE_NOT_THE_SAME;
        } elseif (strlen($user_password_new) < 6) {
            $this->messages[] = Constants::PASSWORD_HAS_A_MINIMUM_LENGTH_OF_6_CHARACTERS;
        } elseif (strlen($userName) > 64 || strlen($userName) < 2) {
            $this->messages[] = Constants::USERNAME_CANNOT_BE_SHORTER_THAN_2_OR_LONGER_THAN_64_CHARACTERS;
        } elseif (!preg_match('/^[a-z\d]{2,64}$/i', $userName)) {
            $this->messages[] = Constants::USERNAME_DOES_NOT_FIT_THE_NAME_SCHEME;
        } elseif (empty($email)) {
            $this->messages[] = Constants::EMAIL_CANNOT_BE_EMPTY;
        } elseif (strlen($email) > 64) {
            $this->messages[] = Constants::EMAIL_CANNOT_BE_LONGER_THAN_64_CHARACTERS;
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->messages[] = Constants::YOUR_EMAIL_ADDRESS_IS_NOT_IN_A_VALID_EMAIL_FORMAT;
        } elseif (!empty($userName)
            && strlen($userName) <= 64
            && strlen($userName) >= 2
            && preg_match('/^[a-z\d]{2,64}$/i', $userName)
            && !empty($email)
            && strlen($email) <= 64
            && filter_var($email, FILTER_VALIDATE_EMAIL)
            && !empty($user_password_new)
            && !empty($user_password_repeat)
            && ($user_password_new === $user_password_repeat)
        ) {
            $result_state = Constants::INT_SUCCESS;

            $this->setEntityManager();
            $ad_user = $this->entity_manager->getRepository(Constants::ENTITY_ADMINUSERS)->findOneByUserName($userName);// "SELECT * FROM users WHERE phone_number = '".$phone_number."'";
            if($ad_user != null){
                $result_state = Constants::ERROR;
                $this->messages[] = Constants::SORRY_THAT_USERNAME_IS_ALREADY_TAKEN;
            }else {
                $ad_user = new AdminUsers();
                if (version_compare(PHP_VERSION, '5.5.0', '<')) {

                    $passwrd = new PasswordCompatibilityLibrary(Constants::PASSWORD_DEFAULT);
                    $user_password_hash = $passwrd->password_hash($user_password_new, Constants::PASSWORD_DEFAULT);

                } else
                    $user_password_hash = password_hash($user_password_new, Constants::PASSWORD_DEFAULT);

                $ad_user->setUserEmail($email);
                $ad_user->setUserName($userName);
                $ad_user->setUserPasswordHash($user_password_hash);
                $this->entity_manager->persist($ad_user);
                $this->entity_manager->flush();
                $this->messages[] = Constants::YOUR_ACCOUNT_HAS_BEEN_CREATED_SUCCESSFULLY_YOU_CAN_NOW_LOG_IN;
            }


        }
        return array('state'=>$result_state,'message'=>$this->messages);
    }

    public function createAPIUserAccount($userName,$password,$rep_password )
    {
        $result_state = Constants::ERROR;
        if (empty($userName)) {
            $this->messages[] = Constants::EMPTY_USERNAME;
        } elseif (empty($password) || empty($rep_password)) {
            $this->messages[] = Constants::EMPTY_PASSWORD;
        } elseif (strcmp($password , $rep_password) !== 0) {
            $this->messages[] = Constants::PASSWORD_DID_ARE_NOT_THE_MATCH;
        } elseif (strlen($password) < 6) {
            $this->messages[] = Constants::PASSWORD_HAS_A_MINIMUM_LENGTH_OF_6_CHARACTERS;
        } elseif (strlen($userName) > 64 || strlen($userName) < 2) {
            $this->messages[] = Constants::USERNAME_CANNOT_BE_SHORTER_THAN_2_OR_LONGER_THAN_64_CHARACTERS;
        } elseif (!preg_match('/^[a-z\d]{2,64}$/i', $userName)) {
            $this->messages[] = Constants::USERNAME_DOES_NOT_FIT_THE_NAME_SCHEME;
        } elseif (!empty($userName)
            && strlen($userName) <= 64
            && strlen($userName) >= 2
            && preg_match('/^[a-z\d]{2,64}$/i', $userName)
            && !empty($password)
            && !empty($rep_password)
            && ($password === $rep_password)
        ) {
            $result_state = Constants::INT_SUCCESS;

            $this->setEntityManager();
            $ad_user = $this->entity_manager->getRepository(Constants::ENTITY_ADMINUSERS)->findOneByUserName($userName);// "SELECT * FROM users WHERE phone_number = '".$phone_number."'";
            if($ad_user != null){
                $result_state = Constants::ERROR;
                $this->messages[] = Constants::SORRY_THAT_USERNAME_IS_ALREADY_TAKEN;
            }else {
                $ad_user = new AdminUsers();
                if (version_compare(PHP_VERSION, '5.5.0', '<')) {

                    $passwrd = new PasswordCompatibilityLibrary(Constants::PASSWORD_DEFAULT);
                    $user_password_hash = $passwrd->password_hash($password, Constants::PASSWORD_DEFAULT);

                } else
                    $user_password_hash = password_hash($password, Constants::PASSWORD_DEFAULT);

                $ad_user->setUserEmail(Constants::DEVELOPERS_MYSHIRI_COM);
                $ad_user->setUserName($userName);
                $ad_user->setUserPasswordHash($user_password_hash);
                $this->entity_manager->persist($ad_user);
                $this->entity_manager->flush();
                $this->messages[] = Constants::YOUR_ACCOUNT_HAS_BEEN_CREATED_SUCCESSFULLY_YOU_CAN_NOW_LOG_IN;
            }


        }
        return array('state'=>$result_state,'message'=>$this->messages);

    }

    public function changeAdminPassword($adminName, $password, $rep_password)
    {
        $result_state = Constants::ERROR;
        if (empty($adminName)) {
            $this->messages[] = Constants::EMPTY_USERNAME;
        } elseif (empty($password) || empty($rep_password)) {
            $this->messages[] = Constants::EMPTY_PASSWORD;
        } elseif (strcmp($password , $rep_password) !== 0) {
            $this->messages[] = Constants::PASSWORD_DID_NOT_THE_MATCH;
        } elseif (strlen($password) < 6) {
            $this->messages[] = Constants::PASSWORD_HAS_A_MINIMUM_LENGTH_OF_6_CHARACTERS;
        } elseif (strlen($adminName) > 64 || strlen($adminName) < 2) {
            $this->messages[] = Constants::USERNAME_CANNOT_BE_SHORTER_THAN_2_OR_LONGER_THAN_64_CHARACTERS;
        } elseif (!preg_match('/^[a-z\d]{2,64}$/i', $adminName)) {
            $this->messages[] = Constants::USERNAME_DOES_NOT_FIT_THE_NAME_SCHEME;
        } elseif (!empty($adminName)
            && strlen($adminName) <= 64
            && strlen($adminName) >= 2
            && preg_match('/^[a-z\d]{2,64}$/i', $adminName)
            && !empty($password)
            && !empty($rep_password)
            && ($password === $rep_password)
        ) {
            $result_state = Constants::INT_SUCCESS;

            $this->setEntityManager();
            $ad_user = $this->entity_manager->getRepository(Constants::ENTITY_ADMINUSERS)->findOneByUserName($adminName);// "SELECT * FROM users WHERE phone_number = '".$phone_number."'";
            if($ad_user != null){
                // $result_state = Constants::ERROR;
                //   $ad_user = new AdminUsers();
                if (version_compare(PHP_VERSION, '5.5.0', '<')) {

                    $passwrd = new PasswordCompatibilityLibrary(Constants::PASSWORD_DEFAULT);
                    $user_password_hash = $passwrd->password_hash($password, Constants::PASSWORD_DEFAULT);

                } else
                    $user_password_hash = password_hash($password, Constants::PASSWORD_DEFAULT);

                $ad_user->setUserPasswordHash($user_password_hash);
                $this->entity_manager->flush();

                $this->messages[] = Constants::YOUR_PASSWORD_HAS_BEEN_CHANGED;
            }else {
                $result_state = Constants::ERROR;
                $this->messages[] = Constants::SORRY_THAT_USERNAME_DOES_NOT_EXIST;
            }


        }
        return array('state'=>$result_state,'message'=>$this->messages);
    }

    public function sendMessage($title, $message)
    {
        $this->setEntityManager();
        $result_state = Constants::ERROR;
        $errTitle = '';
        $errMsg = array();
        if (empty($title)) {
            $errMsg[] = Constants::PLEASE_ENTER_MESSAGE_TITLE;

        } else {

            if (strlen($title) > 64 || strlen($title) < 2) {
                $errMsg[] = Constants::TITLE_CANNOT_BE_SHORTER_THAN_2_OR_LONGER_THAN_64_CHARACTERS;
            }
        }

        if (empty($message)) {
            $errMsg[] = Constants::PLEASE_ENTER_MESSAGE;

        } else {

            if (strlen($message) > 160 ) {
                $errMsg[] = Constants::MESSAGE_MUST_BE_NOT_BE_LONG_THAN_160_CHARACTERS;
            }else if(strlen($message) < 15){
                $errMsg[] = Constants::MESSAGE_MUST_BE_NOT_BE_LESS_THAN_15_CHARACTERS;
            }
        }

        if(!empty($message)
            && strlen($message) <= 160 && strlen($message) >= 15&&
            !empty($title) && strlen($title) <= 64
            && strlen($title) >= 2 )
        {
            $result_state = Constants::INT_SUCCESS;
            $state = "New";
            $personal_msg = Constants::PUBLIC_MESSAGE;//"public";
            date_default_timezone_set("UTC");
            $timestamp = new \DateTime('now');
            $obj_msg = new Messages();
            $obj_msg->setDateTime($timestamp)
                ->setMessage($message)
                ->setState(true)
                ->setTitle($title)
                ->setExtraOne($personal_msg);

            $this->entity_manager->persist($obj_msg);
            $this->entity_manager->flush();
            $account_created = true;
            $resulting_id = $obj_msg->getId();
            if($resulting_id >0){
                $errMsg[] = Constants::NOTIFICATION_NUMBER . $resulting_id. Constants::WAS_SUCCESFULLY_SEND;
                return array('state'=>$result_state,'message'=>$errMsg);
                //TODO enable this
                $gcm_push = new GCMConnect();
                //todo remove error
                $topic = Constants::TOPICS_SHIRI_TOPIC;
                $gcm_topic = array($topic);
                $pushMessage = array("shiri_message" => $message,
                    "title" => self::SHIRI_NEWS);

                $gcm_result = $gcm_push->sendBrocastMessage($gcm_topic, $pushMessage);

                $gcm_result = json_encode($gcm_result);

                if (strpos($gcm_result,'message_id') !== false) {
                    $errMsg[] = Constants::NOTIFICATION_NUMBER . $resulting_id. Constants::WAS_SUCCESFULLY_SEND;
                }else {
                    $errMsg[]= Constants::NOTIFICATION_NOT_SEND;
                }
                //todo use this when sending to multiple pple
               // $errMsg = $this->sendToSelectedPeople($title, $message, $insert_id, $errMsg);


            }else{
                $result_state = Constants::ERROR;
            }
            //   $errMsg[] = "Your account has been created successfully. You can now log in.";
            return array('state'=>$result_state,'message'=>$errMsg);
        }
    }

    /**
     * @param $title
     * @param $message
     * @param $insert_id
     * @param $errMsg
     * @return array
     */
    private function sendToSelectedPeople($title, $message, $insert_id, $errMsg)
    {
//   todo enable for group gcm message sending
        $selected_users = array();
        $arr = array();
        if ($selected_users != null) {
            foreach ($selected_users as $user) {
                $user = new Users();
                $arr[] = $user->getGcmRegid();
            }

            $gcm_push = new GCMConnect();
//todo please check uses of $insert_id
            $pushMessage = array("shiri_message" => $message,
                "title" => $title,
                "id" => $insert_id);
            $gcm_result = $gcm_push->gcm_send_message($arr, $pushMessage);

            $request = json_encode($gcm_result);
            $success = $request['success'];
            $failure = $request['failure'];
            if ($success == 1) {
                // $header_msg = "Notification succesfully send to ";
                $errMsg[] = 'Notification succesfully send !';
                return $errMsg;
            } else {
                $errMsg[] = 'Message succesfully saved!';
                return $errMsg;
            }

        } else {
            $errMsg[] = 'No users Available !';
            return $errMsg;

        }
    }

}