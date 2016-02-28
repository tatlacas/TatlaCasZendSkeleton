<?php
namespace User\Model;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Zend\Crypt\Password\Bcrypt;
use Zend\Session\Container;

/**
 *
 * @author Tatenda Caston Hove
 *        
 */
class UserAuthentication
{

    protected $entity_manager;

    protected $username;

    protected $password;

    protected $user_type;

    protected $full_name;

    /**
     * @return mixed
     */
    public function getFullName()
    {
        return $this->full_name;
    }

    /**
     * @return mixed
     */


    /**
     * @return mixed
     */
    public function getUserType()
    {
        return $this->user_type;
    }

    protected $service_locator;

    const USER_SIGNIN_SESSION_NAME = "user_sigin_session";

    /**
     */
    function __construct($service_locator)
    {
        $this->service_locator = $service_locator;
    }

    public function authenticationPassed()
    {
       $user_session = new Container(UserAuthentication::USER_SIGNIN_SESSION_NAME);
        $this->username=$user_session->username;
        $this->password=$user_session->password;
      return $this->signinSuccessful(false);
    }

    public function signinSuccessful($set_session_variables = true)
    {
        $this->setEntityManager();
        $user=$this->entity_manager->getRepository('Application\Entity\Users')->findOneByUsername($this->getUsername());
        if($user) 
        {
           $this->user_type= $user->getUserType();
               $this->full_name=$user->getFirstName() . ' '. $user->getLastName();

            $hashed_password=$user->getHashedPassword();
            //If its initial login, then use Bcrypt Authentication
         if($set_session_variables)  $signin_info_is_valid = $this->isValid($hashed_password);

             //Otherwise just compare password in db and in session variable
            else  $signin_info_is_valid = $this->isValid_session($hashed_password);
            if($signin_info_is_valid)
            {
               $set_session_variables ? $this->set_session_password($hashed_password):null;
                $this->set_session_variables();
                return true;
            }
        }
        //If we reach here, invalid login information
        return false;
    }

    private function set_session_password($hashed_password)
    {
        $user_session = new Container(UserAuthentication::USER_SIGNIN_SESSION_NAME);
        $user_session->password=$hashed_password;
    }

    private function set_session_variables()
    {
        $user_session = new Container(UserAuthentication::USER_SIGNIN_SESSION_NAME);
        $user_session->username=$this->username;
        $user_session->user_type=$this->user_type;
    }

    public function isValid_session($session_hash)
    {
        return $session_hash === $this->getPassword();
    }
    
    public function isValid($hash)
    {
        $bcrypt = new Bcrypt();
        return $bcrypt->verify($this->getPassword(), $hash);
    }
    
    
    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = strtolower($username);
        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function getEntityManager()
    {
        return $this->entity_manager;
    }

    public function setEntityManager()
    {
        $this->entity_manager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        return $this;
    }

    public function getServiceLocator()
    {
        return $this->service_locator;
    }
}

?>