<?php
/**
 * TatlaCas Customized
 *
 *
 * @copyright Copyright (c) 20013-2014 Fundamental Technologies (Private) Limited (http://www.funtechno.com)
 * @author   Tatenda Caston Hove <tathove@gmail.com> on 01/09/2015.
 *
 */


namespace Mobile\Model;

use Application\Entity\UserActivitiesData;
use Application\Entity\CronJobs;
use Application\Model\Constants;
use Application\Model\DoctrineInitialization;
use Zend\Session\Container;

class DBUtils extends DoctrineInitialization
{



    function __construct($service_locator)
    {
        parent::__construct($service_locator);
    }


    public function save_individual_client_messages($title, $message, $phone_number)
    {
        $this->setEntityManager();
        date_default_timezone_set("UTC");
        $now = new \DateTime();
        $timestamp = $now->getTimestamp();
        $user_activity = new UserActivitiesData();
        $user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($phone_number);

        if ($user ==null) {
            //user not found
            $this->return_die(Constants::ON_FAILURE_CONST);
        }

        try {
            $user_activity->setMessage($message)
                ->setTitle($title)
                ->setUser($user)
                ->setDateTime($timestamp)
                ->setMsgId(Constants::SERVER_MSG);
            $this->entity_manager->persist($user_activity);
            $this->entity_manager->flush();
            $id = $user_activity->getId();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function save_cron_job($title, $job_id, $phone_number)
    {
      //  die($title.$job_id.$phone_number);
        $this->setEntityManager();
        date_default_timezone_set("UTC");
        $now = new \DateTime();
        $timestamp = $now->getTimestamp();
        $job_state = true;

        $cjob = new CronJobs();
        $user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($phone_number);

        if ($user ==null) {
            //user not found
            $this->return_die(Constants::ON_FAILURE_CONST);
        }
     //   echo($title.$job_id.$phone_number);
        $cjob->setDateTime($now);
        $cjob->setJobId($job_id);
        $cjob->setJobState($job_state);
        $cjob->setTitle($title);
        $cjob->setUser($user);
        try {
            $this->entity_manager->persist($cjob);
            $this->entity_manager->flush();
            return true;
        } catch (\Exception $e) {
            die($e);
        }


    }


    public function schedule_job($cron_data, $cronJob_url)
    {
        $jsonDataEncoded = json_encode($cron_data);
        $cron_data = http_build_query($cron_data);
        $url = $cronJob_url . $cron_data;
        $ch = curl_init($url);
        // curl_setopt($ch, CURLOPT_URL, $cronJob_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        //    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $matches = array();
        $t = preg_match('/"id":(.*?)\,"groupId"/s', $result, $matches);
        return $matches[1];
    }

    function send_notification($gcm_reg_id, $title, $message)
    {
        $gcm_push = new GCMConnect();
        //$res = $db->save_gcm_client_details($name." ".$sname, "", $gcm_reg_id);

        $registatoin_ids = array($gcm_reg_id);
        $pushMessage = array(Constants::SHIRI_MESSAGE => $message,
            Constants::TITLE => $title);
        $gcm_result = $gcm_push->gcm_send_message($registatoin_ids, $pushMessage);
    }

    public function remove_cronJobs($job_id)
    {
        $cronJob = "https://www.setcronjob.com/api/cron.delete?token=4nzu6xyv1o38o1kukkhv1pvfbvhhfivi&id=" . $job_id;
        $ch = curl_init($cronJob);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $matches = array();
        $t = preg_match('/"id":(.*?)\,"groupId"/s', $result, $matches);
        return $matches[1];
    }

    function return_die($value)
    {
        $xml_output = "<?xml version=\"1.0\"?>\n";
        $xml_output .= "<entries>\n";
        $xml_output .= "\t<entry>\n";
        $xml_output .= "\t\t<result>" . $value . "</result>\n";
        $xml_output .= "\t</entry>\n";
        $xml_output .= "</entries>";
        die($xml_output);
    }




}