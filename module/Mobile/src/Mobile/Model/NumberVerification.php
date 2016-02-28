<?php
/**
 * Created by PhpStorm.
 * User: alois
 * Date: 9/10/2015
 * Time: 11:06 AM
 */

namespace Mobile\Model;


use Application\Entity\Referrals;
use Application\Model\Constants;
use Application\Model\DoctrineInitialization;
use Application\Model\infobipSMSMessaging;
use Application\Model\NexmoMessage;

class NumberVerification extends DoctrineInitialization
{

    function __construct($service_locator)
    {
        parent::__construct($service_locator);
    }

    public function verifyNumber($phone_number)
    {

        //   if ($request != NULL) {
        //todo save for statical reasons
        //   $phone_number = $request['phone_number'];
        $code1 = mt_rand(100, 999);
        $code2 = mt_rand(300, 888);
        $nexmoCode = $code1 . " " . $code2;//mt_rand(100000,999999);
        // $NMessageObj = new NexmoMessage("9bea963b","5214fca6");

        //$phone_number = str_replace(' ', '', $phone_number);
//            if(substr($phone_number, 0, 1) !== "+") {
//                $phone_number = "+".$phone_number;
//            }

//            $db = new DBUtils($this->service_locator);
//           // $phone_number  = "+263783211562";
//            $res = $db->save_individual_client_messages("Shiri", Constants::SMS_VERIFY.$nexmoCode
//                , $phone_number);
//            $result = $NMessageObj->sendText($phone_number,"Shiri",Constants::SMS_VERIFY.$nexmoCode,null);
//            $result = $NMessageObj->checkSendStatusSuccess($result);
        if (strlen($phone_number) <= Constants::PHONE_NUMBER_LENGTH) {
            $phone_number = htmlspecialchars($phone_number);
            $infobipSMSMessaging = new infobipSMSMessaging();
            //    $phone_number  = "+263783211562";
            if (Constants::ENVIRONMENT_TYPE == Constants::LIVE_ENVIRONMENT)
                $result = $infobipSMSMessaging->sendmsg($phone_number, Constants::SHIRI_NAME, Constants::SMS_VERIFY . $nexmoCode);
            else $result = "not_empty";

            if (empty($result)) {
                //Failed to send SMS
                $this->return_die(Constants::ON_FAILURE_CONST);
            }

           // $referred_by = $this->getReferrer($phone_number);
            $xml_output = "<?xml version=\"1.0\"?>\n";
            $xml_output .= "<entries>\n";
            $xml_output .= "\t<entry>\n";
            $xml_output .= "\t\t<result>" . Constants::ON_SUCCESS_CONST . "</result>\n";
            $xml_output .= "\t\t<smsKey>" . $nexmoCode . "</smsKey>\n";
            $xml_output .= "\t</entry>\n";
           // $xml_output .= $referred_by;
            $xml_output .= "</entries>";
            return $xml_output;

        } else {
            //Failed no Phone Number Set
            return $this->return_die(Constants::ON_FAILURE_CONST);

        }

    }

    function return_die($value)
    {
        $xml_output = "<?xml version=\"1.0\"?>\n";
        $xml_output .= "<entries>\n";
        $xml_output .= "\t<entry>\n";
        $xml_output .= "\t\t<result>" . $value . "</result>\n";
        $xml_output .= "\t</entry>\n";
        $xml_output .= "</entries>";
        return ($xml_output);
    }

    private function getReferrer($phone_number)
    {

        $this->setEntityManager();
        $result = $this->entity_manager->getRepository(Constants::ENTITY_REFERRALS)->findOneByReferredPhoneNumber($phone_number);
        if ($result != null) {

            $date_t = $result->getDateReferred();
            $date_t1 = $date_t / 1000;
            $date = new \DateTime();
            $date->setTimestamp($date_t1);
            $today = new \DateTime('now');
            $interval = $date->diff($today, true);
            if ($interval && $interval->days <= Constants::REFERRAL_LOCK_PERIOD) {
                $referrer = $result->getReferrer();
                $result = '<referredBy id="' . $referrer->getUserId() . '" dateReferred="'.$date_t.'">' . $referrer->getPhoneNumber() . '</referredBy>';
                return $result;
            }


        }

        $result = "<referredBy>-1</referredBy>";
        return $result;
    }
}