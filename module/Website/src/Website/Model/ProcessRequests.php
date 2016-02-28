<?php
/**
 * Created by PhpStorm.
 * User: alois - mumeraalois@gmail.com
 * Date: 10/24/2015
 * Time: 1:22 AM
 */

namespace Website\Model;


use Application\Entity\Users;
use Application\Model\Constants;
use Application\Model\DoctrineInitialization;
use Mobile\Model\Utils;

class ProcessRequests extends  DoctrineInitialization
{
    function __construct($service_locator)
    {
        parent::__construct($service_locator);
    }

    public function returnAllUsers(){
        $this->setEntityManager();
        $util = new Utils();

        $data = array();
        $count = 0;
       // $users = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findAll();
        $query = $this->entity_manager->createQueryBuilder();
        $query->select(array('u'))
            ->from(Constants::ENTITY_USERS, 'u')
            ->orderBy('u.userId', 'DESC')
            ->setMaxResults(20);
        $query = $query->getQuery();
        $data_result = $query->getResult();
        if ($data_result == null) {
            //no users
            $data[$count] = 'no new users available';
            return array('state'=>Constants::ERROR,'userDt'=>$data);
        }


        foreach($data_result as $user){
          // $user =  new Users();
            $count += 1;
            $fname = $user->getFirstName();
            $lname = $user->getLastName();
            $gcm_reg_id = $user->getGcmRegid();
            $Joined_date = $util->MillisecondsToDateTimeString($user->getCreatedAt(),0);

            $data[$count] = array('first_name'=> $fname,
                'last_name'=> $lname,
                'jdate'=>$Joined_date,
                'gcm_regid'=>$gcm_reg_id);
        }
        $users = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findAll();
        return array('state'=>Constants::INT_SUCCESS, 'userDt'=>$data,'user_count'=>count($users));
    }

    public function returnUserData($phoneNumber)
    {
        $this->setEntityManager();
        $user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($phoneNumber);
        return $user;

    }


}