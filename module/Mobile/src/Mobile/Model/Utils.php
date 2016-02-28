<?php
/**
 * Created by PhpStorm.
 * User: alois - mumeraalois@gmail.com
 * Date: 9/14/2015
 * Time: 11:12 AM
 */

namespace Mobile\Model;


use Application\Model\Constants;
use DOMDocument;
use SimpleXMLElement;

class Utils
{

    const TO_SECCONDS = 1000;

    /**
     * @param $value
     * @return string
     */
    public function return_results($value){
        $xml_output = "<?xml version=\"1.0\"?>\n";
        $xml_output .= "<entries>\n";
        $xml_output .= "\t<entry>\n";
        $xml_output .= "\t\t<result>" . $value. "</result>\n";
        $xml_output .= "\t</entry>\n";
        $xml_output .= "</entries>";
        return $xml_output;
    }

    /**
     * @param $digits
     * @return int
     */
    public function generatePin($digits)
    {
        return rand(pow(10, $digits - 1) - 1, pow(10, $digits) - 1);
    }
    /**
     * @param $millis
     * @param $isWhat
     * @return string
     */
    public function MillisecondsToDateTimeString($millis,$isWhat){
       // $millis= $millis;
        $millis = $millis/1000;
        $date = new \DateTime();
        $date = $date->setTimestamp($millis);
        if($isWhat == Constants::INT_BUNDLE_ONE){
            return $date->format('Y-m-d');
        }
        return $date->format('Y-m-d H:m:s');
    }

    /**
     * @param $millis
     * @param $isWhat
     * @return string
     */
    public function toWordsDtString($millis,$isWhat){
        // $millis= $millis;
        $millis = $millis/1000;
        $date = new \DateTime();
        $date = $date->setTimestamp($millis);
        if($isWhat == Constants::INT_BUNDLE_ONE){
            return $date->format('Y-M-d');
        }
        return $date->format('Y-M-d H:m:s');
    }
    /**
     * @return string
     */
    public function returnCurrentTimeString(){
        date_default_timezone_set('UTC');
        $date = new \DateTime("now");
        //$bc_datetime = $date->getTimestamp();
        return $date->format('m-d-Y-H-m-s');
    }

    /**
     * @return int
     */
    public function returnCurrentMilliseconds(){
        date_default_timezone_set('UTC');
        $date = new \DateTime("now");
        return $date->getTimestamp()*1000;
    }
    public function notifyPayments($gcm_reg_id, $message,$amount_paid){
        $gcm_push = new GCMConnect();
        $registatoin_ids = array($gcm_reg_id);
        $pushMessage = array(Constants::SHIRI_MESSAGE => $message ,
            Constants::TITLE => Constants::NETTCASH_PAYMENT_TYPE,
            Constants::AMOUNT => $amount_paid);

        $gcm_result = $gcm_push->gcm_send_message($registatoin_ids, $pushMessage);
    }

    /**
     * @param $id
     * @return string
     */
    public function generatePolicyNumber($id)
    {

        $policy_number = (string)($id += Constants::POLICY_FIRST_GENERATION_NUMBER);

        if (strlen($policy_number) == Constants::EXTRA_FOUR_DIGITS) {
            $policy_number = Constants::POLICY_FIRST_GEN . $policy_number;
            return $policy_number;
        } else if (strlen($policy_number) == Constants::EXTRA_FIVE_DIGITS) {
            $policy_number = Constants::POLICY_SECOND_GEN . $policy_number;
            return $policy_number;
        } else if (strlen($policy_number) == Constants::EXTRA_SIX_DIGITS) {
            $policy_number = Constants::POLICY_THIRD_GEN . $policy_number;
            return $policy_number;
        } else if (strlen($policy_number) == Constants::EXTRA_SEVEN_DIGITS) {
            $policy_number = Constants::POLICY_FORTH_GEN . $policy_number;
            return $policy_number;
        } else {
            //todo Constants::POLICY_SECOND_GENERATION_NUMBER
        }
        return $policy_number;
    }

    /**
     * @param $result_code
     * @param $result_description
     * @return string
     */
    public function onfailure($result_code,$result_description){
        header('Content-Type: text/xml');
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><entries/>');
        $entries= $xml->addChild('entry');

        $entries->addChild('result_code', $result_code);
        $entries->addChild('result_description', $result_description);

        $dom = new DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());
        return $dom->saveXML();
    }

    /**
     * @param $result_code
     * @param $result_description
     * @return string
     */
    public function response($result_code,$result_description){
        header('Content-Type: text/xml');
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><entries/>');
        $entries= $xml->addChild('entry');

        $entries->addChild('result_code',$result_code);
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

    /**
     * @param $date1
     * @param $date2
     * @return int
     */
    public function number_ofmonths($date1, $date2)
    {
        $begin = new \DateTime( $date1 );
        $end = new \DateTime( $date2 );
        $end = $end->modify( '+1 month' );

        $interval = \DateInterval::createFromDateString('1 month');

        $period = new \DatePeriod($begin, $interval, $end);
        $counter = 0;
        foreach($period as $dt) {
            $counter++;
        }

        return $counter;
    }

    /**
     * @param $date
     * @return mixed
     */
    public function firstDayOfMonth($date){
        //  $due_Date= $dt;
        // $due_Date = 1420142400000/1000;

        // date_default_timezone_set('UTC');
        // $date = new \DateTime($dt.'');
        // $date->setTimestamp($due_Date);
        //   $dat = $date->format('Y-m-d H:i:s');

        $dt = $date->modify("first day of next month");

        $date = $dt->format('Y-m-d H:i:s');
        // $date = new \DateTime($dt);
        return ($date);
//        $now = new \DateTime($date);
//        $timestamp = $now->getTimestamp();
//        $dt = date('Y-m-d H:i:s', $due_Date);
//        die($dt);
//        return $timestamp.'';
    }

    /**
     * @param $dt
     * @param $currentDate
     * @return array|null
     */
    public function diffMonths($dt, $currentDate){

//        $mil = $dt;
//        $seconds = $mil / self::TO_SECCONDS;
//        $dt = date("d-m-Y", $seconds);
//
//        $mil = $currentDate;
//        $seconds = $mil / self::TO_SECCONDS;
//        $currentDate = date("d-m-Y", $seconds);

        $ts1 = strtotime($dt->format('Y-m-d H:i:s'));
        $ts2 = strtotime($currentDate->format('Y-m-d H:i:s'));

        $year1 = date('Y', $ts1);
        $year2 = date('Y', $ts2);

        $month1 = date('m', $ts1);
        $month2 = date('m', $ts2);
        // die($month1.$month2);
        $diff = (int)(($year2 - $year1) * 12) + ($month2 - $month1);

        $result=array();
        if($diff)
        {
            $i=0;
            while($i<$diff)
            {
                if($month1==12)
                {
                    $year1+=1;
                    $month1=1;
                }else{
                    $month1+=1;
                }
                $number = cal_days_in_month(CAL_GREGORIAN, $month1, $year1);
                $result[$i++] = $year1.'-'.$month1.'-'.$number;
            }
            return $result;
        }
        return null;
    }

    /**
     * @param $startdate
     * @param $currentDate
     * @return bool
     */
    public function compareDates($startdate,$currentDate){
        //
//        $mil = $startdate;
//        $seconds = $mil / self::TO_SECCONDS;
//        $cdate = date("d-m-Y", $seconds);
//
//        $mil = $currentDate;
//        $seconds = $mil / self::TO_SECCONDS;
//        $currentDate = date("d-m-Y", $seconds);
        // die($cdate.' '.$currentDate);
        // date_default_timezone_set("UTC");
        //  $startdate = new \DateTime($startdate);
        //  $currentDate = new \DateTime($currentDate);
        if($startdate < $currentDate){
            return true;
        }else{
            return false;
        }

    }

    /**
     * @param $encrypt
     * @param $key
     * @return string
     */
    function mc_encrypt($encrypt, $key){
        $encrypt = serialize($encrypt);
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC), MCRYPT_DEV_URANDOM);
        $key = pack('H*', $key);
        $mac = hash_hmac('sha256', $encrypt, substr(bin2hex($key), -32));
        $passcrypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $encrypt.$mac, MCRYPT_MODE_CBC, $iv);
        $encoded = base64_encode($passcrypt).'|'.base64_encode($iv);
        return $encoded;
    }

}