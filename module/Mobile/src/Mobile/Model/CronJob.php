<?php
/**
 * Created by PhpStorm.
 * User: alois
 * Date: 9/9/2015
 * Time: 9:58 AM
 */
namespace Mobile\Model;

use Zend\Session\Container;

class CronJob
{

    public $token;
    public $uri = 'https://www.easycron.com/rest/';


    function __construct($token)
    {
        $this->token = $token;
    }

    public function call_schedule_job($method, $data = array())
    {
        $data['token'] = $this->token;
        $arguments = array();
        foreach ($data as $name => $value) {
            $arguments[] = $name . '=' . urlencode($value);
        }
        $temp = implode('&', $arguments);

        $url = $this->uri . $method . '?' . $temp;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        $result = curl_exec($ch);
        // $result = file_get_contents($url);
//
//        if ($result) {
//            return json_decode($result, true);
//        } else {
//            return $result;
//        }
        return $result;
    }
}