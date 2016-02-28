<?php
/**
 * Created by PhpStorm.
 * User: alois - mumeraalois@gmail.com
 * Date: 9/14/2015
 * Time: 11:03 AM
 */

namespace Mobile\Model;


use Application\Model\DoctrineInitialization;
use Exception;

class NettCashUserRegistration extends DoctrineInitialization
{

    function __construct($service_locator)
    {
        parent::__construct($service_locator);
    }

    public function nettcashRegistration($request){
       // $this->setEntityManager();
        $util = new Utils();
        if( $request != NULL)
        {
            $url = $request['url'];
            $client = $request['client'];
            $agentid = $request['agentid'];
            $password = $request['password'];
            $firstname =$request['firstname'];
            $lastname = $request['lastname'];
            $idnumber =$request['idnumber'];
            $sms =$request['sms'];
            $referenceid = $request['referenceid'];

            $main_url = $url."client=".$client."&agentid=".$agentid."&password=".$password.
                "&firstname=".$firstname."&lastname=".$lastname."&idnumber=".$idnumber."&sms=".$sms.
                "&referenceid=".$referenceid;
            $fields_string = null;
            $fields = null;
            $fields = array(
                "client" => $client."&",
                "agentid" => $agentid."&",//"3016440152130359&",this is the code
                "password" => $password."&",//"3a8947cfa0cc2d86986c8f5fa866d251&",md5(password)
                "firstname" => $firstname."&",
                "lastname" => $lastname."&",
                "idnumber" => $idnumber."&",
                "sms" => $sms."&",
                "referenceid" => $referenceid);
            foreach ($fields as $key => $value) {
                $fields_string .= $key . "=" . $value; //. "&";
            }
            rtrim($fields_string, "& ");

            $url_tagpay = 'http://196.29.39.14:82/agenthub01/test/api/tpenrollment.php?';
            $http_request = $url_tagpay . $fields_string;
            $ch = curl_init($http_request);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.52 Safari/537.17');
            curl_setopt($ch, CURLOPT_AUTOREFERER, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_VERBOSE, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

            try{
                $resp = curl_exec($ch);
                header("Content-type: text/xml");
                if(curl_errno($ch)){
                    echo 'Curl error: ' . curl_error($ch);
                }
            }catch (Exception $ex)
            {
                die("eror");
            }
            $xml = simplexml_load_string($resp);



            curl_close($ch);
           // die($xml_output);
            return $util->return_results($xml->result );
        }else{
           return $util->return_results('0');
        }
    }


}