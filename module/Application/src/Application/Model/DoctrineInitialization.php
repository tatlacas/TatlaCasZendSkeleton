<?php
/**
 * TatlaCas Customized
 *
 * 
 * @copyright Copyright (c) 20013-2014 Fundamental Technologies (Private) Limited (http://www.fundamentaltechno.com)
 * @author   Tatenda Caston Hove <tathove@gmail.com> on 1/19/14. 
 * 
 */


namespace Application\Model;


class DoctrineInitialization {
    protected $entity_manager;
    protected $service_locator;


    function __construct($service_locator)
    {
        $this->service_locator = $service_locator;
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