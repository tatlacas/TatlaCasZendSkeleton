<?php
namespace Website\Model;

use Application\Entity\AdminUsers;
use Application\Model\Constants;
use Application\Model\DoctrineInitialization;
use Zend\Session\Container;

/**
 * Created by PhpStorm.
 * User: alois - mumeraalois@gmail.com
 * Date: 10/19/2015
 * Time: 6:36 PM
 */
class AdminLogin extends DoctrineInitialization
{

    const APPLICATION_SHIRI_LOGIN = 'applicationShiriLogin';
    public $sess ;
    const LOGGED_OUT = 1;
    const LOGGED_IN = 2;

    function __construct($service_locator)
    {
        parent::__construct($service_locator);
        $sess = new Container(self::APPLICATION_SHIRI_LOGIN);
//        $sess->loging_state = self::LOGGED_OUT;
//        $sess->loging_state = self::LOGGED_IN;
//        if(isset($sess->loging_state) && $sess->loging_state == self::LOGGED_IN){
//            $this->dologinWithPostData();
//        }else if(isset($sess->loging_state)&& $sess->loging_state == self::LOGGED_OUT){
//            $this->doLogout();
//        }
    }

    public function isUserLoggedIn()
    {

//        if(Zend_Session::sessionExist()){
//            return true;
//        }
        return false;
    }

    public function dologinWithPostData($username)
    {
        $this->setEntityManager();
        $data =  array();
        $ad_user = $this->entity_manager->getRepository(Constants::ENTITY_ADMINUSERS)->findOneByUserName($username);// "SELECT * FROM users WHERE phone_number = '".$phone_number."'";
        if ($ad_user != null) {
        //    $ad_user = new AdminUsers();
            $user_password_hash = $ad_user->getUserPasswordHash();
            $data = array('password'=>$user_password_hash,
                'state'=>Constants::ADMIN_EXISTS);
        }else {
            $data = array('state'=>Constants::NO_ADMIN);
        }
        return $data;
    }

    public function doLogout()
    {

    }



}