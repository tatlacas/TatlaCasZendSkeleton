<?php
/**
 * Created by PhpStorm.
 * User: alois - Email : mumeraalois@gmail.com
 * Date: 9/13/2015
 * Time: 8:51 PM
 */

namespace Mobile\Model;


use Application\Entity\Messages;
use Application\Model\Constants;
use Application\Model\DoctrineInitialization;
use Zend\I18n\Validator\DateTime;

class UserNotifications extends DoctrineInitialization
{

    function __construct($service_locator)
    {
        parent::__construct($service_locator);
    }

    public function fetchNotification($datetime){
        return $this->return_response(Constants::ON_FAILURE_CONST);
        //todo test this method to be live
        $priv = Constants::PRIVATE_MESSAGE;//"private";
        $this->setEntityManager();
        $date_time = new \DateTime();
        $date_time = $date_time->setTimestamp($datetime/1000);
        $queryBuilder = $this->entity_manager->createQueryBuilder();
        $queryBuilder->select(array('m'))
            ->from(Constants::ENTITY_MESSAGES, 'm')
            ->where($queryBuilder->expr()->orX(
                $queryBuilder->expr()->gt('m.dateTime', '?1'),
                $queryBuilder->expr()->neq('m.extraOne', '?2')
            ))
//            ->andWhere($queryBuilder->expr()->orX(
//                $queryBuilder->expr()->neq('m.extraOne', ':extra')
//            ))
            ->setParameters(array(
                1 => $date_time,
                2 => $priv,
            ));
        $query = $queryBuilder->getQuery();
        $notif_result = $query->getResult();
        if($notif_result != null){
            $xml_output = "<?xml version=\"1.0\"?>\n";
            $xml_output .= "<entries>\n";
            foreach($notif_result as $message){
               // $message = new Messages();
                $xml_output .= "\t<entry>\n";
                $xml_output .= "\t\t<message>" . $message->getMessage() . "</message>\n";
                $xml_output .= "\t\t<title>" . $message->getTitle() . "</title>\n";
                $xml_output .= "\t\t<state>" . $message->getState() . "</state>\n";
                $xml_output .= "\t\t<id>" . $message->getId() . "</id>\n";
                $xml_output .= "\t\t<date_time>" . $message->getDateTime() . "</date_time>\n";
                $xml_output .= "\t\t<result>" . Constants::ON_SUCCESS_CONST. "</result>\n";
                $xml_output .= "\t</entry>\n";
            }
            $xml_output .= "</entries>";
            return $xml_output;
        }else{
            return $this->return_response(Constants::ON_FAILURE_CONST);
        }
    }

    private function return_response($string)
    {
        $xml_output = "<?xml version=\"1.0\"?>\n";
        $xml_output .= "<entries>\n";
        $xml_output .= "\t<entry>\n";
        $xml_output .= "\t\t<result>" . $string. "</result>\n";
        $xml_output .= "\t</entry>\n";
        $xml_output .= "</entries>";
        return $xml_output;
    }

}