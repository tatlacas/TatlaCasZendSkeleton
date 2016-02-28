<?php
/**
 *@author: Tatenda Caston Hove
 * Date: 12/30/13
 * Time: 8:38 PM
 */

namespace User\Model;

use Zend\Session\Container;
use Application\Entity\Users;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

class UserAuthorization {

    protected $module_to_be_authorized;
    protected $controller_to_be_authorized;
    protected $resource_to_be_authorized;
    protected $entity_manager;
    protected $username;


    protected $service_locator;

    /**
     */

    /**
     * @return mixed
     */

    public function getUsername()
    {
        $user_session = new Container(UserAuthentication::USER_SIGNIN_SESSION_NAME);
        return $this->username = $user_session->username;
    }

    /**
     *
     */
    public function setEntityManager()
    {
        $this->entity_manager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        return $this;
    }


    public function getServiceLocator()
    {
        return $this->service_locator;
    }

    function __construct($service_locator)
    {
        $this->service_locator = $service_locator;
        $user_session = new Container(UserAuthentication::USER_SIGNIN_SESSION_NAME);
        $this->username=$user_session->username;
    }
    /**
     * @param mixed $controller_to_be_authorized
     */
    public function setControllerToBeAuthorized($controller_to_be_authorized)
    {
        $this->controller_to_be_authorized = $controller_to_be_authorized;
    }

    /**
     * @return mixed
     */
    public function getControllerToBeAuthorized()
    {
        return $this->controller_to_be_authorized;
    }

    /**
     * @param mixed $module_to_be_authorized
     */
    public function setModuleToBeAuthorized($module_to_be_authorized)
    {
        $this->module_to_be_authorized = $module_to_be_authorized;
    }

    /**
     * @return mixed
     */
    public function getModuleToBeAuthorized()
    {
        return $this->module_to_be_authorized;
    }

    /**
     * @param mixed $resource_to_be_authorized
     */
    public function setResourceToBeAuthorized($resource_to_be_authorized)
    {
        $this->resource_to_be_authorized = $resource_to_be_authorized;
    }

    /**
     * @return mixed
     */
    public function getResourceToBeAuthorized()
    {
        return $this->resource_to_be_authorized;
    }

    public function grant_access()
    {
        $this->setEntityManager();
        $user=$this->entity_manager->getRepository('Application\Entity\Users')->findOneByUsername($this->getUsername());
        if(!$user) return false;
        $module = $this->entity_manager->getRepository('Application\Entity\Modules')->findOneByName($this->getModuleToBeAuthorized());
        if(!$module)return false;
        $controller = $this->entity_manager->getRepository('Application\Entity\Controllers')
            ->findOneBy(array('name'=>$this->getControllerToBeAuthorized(),
                                'module'=>$module));
        if(!$controller)return false;
        $resource = $this->entity_manager->getRepository('Application\Entity\Resources')
            ->findOneBy(array('name'=>$this->getResourceToBeAuthorized(), 'controller'=>$controller));
            if(!$resource)return false;
        $is_user_privileged = $this->entity_manager->getRepository('Application\Entity\PrivilegesUsers')
            ->findOneBy(array('user'=>$user,'resource'=>$resource));
        $access_granted=$is_user_privileged?true:false;
            return $access_granted;
    }
} 