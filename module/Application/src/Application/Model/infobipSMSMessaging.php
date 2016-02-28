<?php
/**
 * Created by PhpStorm.
 * User: alois
 * Date: 9/10/2015
 * Time: 4:42 PM
 */

namespace Application\Model;




use Zend\Json\Server\Exception\HttpException;

class infobipSMSMessaging
{

    function __construct()
    {
    }

    public function sendmsg($to, $from,$message){

        $url = 'http://api2.infobip.com/api/sendsms/plain?';
        $phone_number = str_replace('+', '', $to);
        $jsonData = array(
            'user' => 'Balltron',
            'password' => 'fSidsPVS',
            'sender' => $from,
            'SMSText' => $message,
            'GSM' => $phone_number
        );
        $cron_data = http_build_query($jsonData);
        $url = $url.$cron_data;
        $crl = curl_init($url);
        curl_setopt($crl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        //  curl_setopt($crl, CURLOPT_POSTFIELDS, $jsonDataEncoded);
      //  http://api2.infobip.com/api/sendsms/plain?user=Balltron&password=fSidsPVS&sender=Shiri&SMSText=MESSAGE&GSM=263783211562
        curl_setopt($crl, CURLOPT_POST,true);
        curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($crl);

        curl_close($crl);
        return $response;





//        $request = new \HttpRequest();
//        $request->setUrl('https://api.infobip.com/sms/1/text/single');
//        $request->setMethod(HTTP_METH_POST);
//        $username = 'Balltron';
//            $password = 'fSidsPVS';
//        $string = $username.':'.$password;
//        $encoded = base64_encode($string);
//        $request->setHeaders(array(
//            'accept' => 'application/json',
//            'content-type' => 'application/json',
//            'authorization' => 'Basic '.$encoded.'=='
//        ));
//
//        $request->setBody('{
//   "from":'.$from.',
//   "to":'.$to.',
//   "text":'.$message.'
//}');
//
//        try {
//            $response = $request->send();
//
//            return $response->getBody();
//        } catch (HttpException $ex) {
//            return $ex;
//        }
    }


}