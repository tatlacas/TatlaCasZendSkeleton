<?php
/**
 * Created by PhpStorm.
 * User: alois
 * Date: 9/13/2015
 * Time: 8:10 PM
 */

namespace Mobile\Model;


use Application\Entity\Users;
use Application\Model\Constants;
use Application\Model\DoctrineInitialization;
use Application\Model\PasswordCompatibilityLibrary;

class AccountLogin extends DoctrineInitialization
{

    function __construct($service_locator)
    {
        parent::__construct($service_locator);
    }

    public function login($phone_number, $pincode){
        if (version_compare(PHP_VERSION, '5.3.7', '<')) {
            return Constants::xmlError(Constants::SORRY_SHIRI_DOES_NOT_RUN);
        }
        $this->setEntityManager();
        $pin_code = "";
        if(strlen($pincode) === Constants::PINCODE_MIN_LENGTH  && strlen($phone_number) <= Constants::PHONE_NUMBER_LENGTH) {
            $pincode = htmlspecialchars($pincode);
            $phone_number = htmlspecialchars($phone_number);
            $user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($phone_number);// "SELECT * FROM users WHERE phone_number = '".$phone_number."'";
            if ($user == null) {
                //no user
                return $this->return_response(Constants::ON_WRONG_NUMBER_CONST);
            }else{
             //   foreach($user as $u){
                    $t = new Users();
                    $pin_code =  $user->getPincode();
                    $output = -1;
                    $verified = false;
                    if (version_compare(PHP_VERSION, '5.5.0', '<')) {

                        $passwrd = new PasswordCompatibilityLibrary(1);
                        $verified = $passwrd->password_verify($pincode, $pin_code);
                    }else {
                        $verified = password_verify($pincode, $pin_code);
                    }
                    if ($verified) {
                        $output =  Constants::ON_SUCCESS_CONST;
                    }else {
                        $output = Constants::ON_FAILURE_CONST;
                    }
                    return $this->return_response($output);
              //  }
            }



        }else{
            //wrong parameters
            return $this->return_response(Constants::ON_FAILURE_CONST);
        }

       }
    function return_response($value)
    {
        $xml_output = "<?xml version=\"1.0\"?>\n";
        $xml_output .= "<entries>\n";
        $xml_output .= "\t<entry>\n";
        $xml_output .= "\t\t<result>" . $value . "</result>\n";
        $xml_output .= "\t</entry>\n";
        $xml_output .= "</entries>";
        return ($xml_output);
    }

}