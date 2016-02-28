<?php
namespace Website;

use Application\Model\Constants;
use Zend\Paginator\Paginator;
use Website\Model\Paginator\Adapter as PaginatorAdapter;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Website\Model\Paginator\Repository\UserRepository'        => function ($serviceManager) {

                    $entityManager  = $serviceManager->get(
                        'Doctrine\ORM\EntityManager'
                    );

                    $userRepository = $entityManager
                        ->getRepository(Constants::ENTITY_USERS);

                    $adapter        = new PaginatorAdapter($userRepository);

                    $page           = $serviceManager
                        ->get('application')
                        ->getMvcEvent()
                        ->getRouteMatch()
                        ->getParam('page');

                    $paginator      = new Paginator($adapter);
                    $paginator->setCurrentPageNumber($page)
                        ->setItemCountPerPage(Constants::ITEMS_PER_PAGE); // how many items per page

                    return $paginator;
                },
                //todo add more links for pagination
                'Website\Model\Paginator\Repository\EcoPaymentsRepository'        => function ($serviceManager) {
                    $entityManager  = $serviceManager->get(
                        'Doctrine\ORM\EntityManager'
                    );

                    $userRepository = $entityManager
                        ->getRepository(Constants::ENTITY_ECOCASH);

                    $adapter        = new PaginatorAdapter($userRepository);

                    $page           = $serviceManager
                        ->get('application')
                        ->getMvcEvent()
                        ->getRouteMatch()
                        ->getParam('page');

                    $paginator      = new Paginator($adapter);
                    $paginator->setCurrentPageNumber($page)
                        ->setItemCountPerPage(Constants::ITEMS_PER_PAGE); // how many items per page

                    return $paginator;
                },
                'Website\Model\Paginator\Repository\NetPaymentsRepository'        => function ($serviceManager) {
                    $entityManager  = $serviceManager->get(
                        'Doctrine\ORM\EntityManager'
                    );

                    $userRepository = $entityManager
                        ->getRepository(Constants::ENTITY_FROM_NETTCASH_SERVER);

                    $adapter        = new PaginatorAdapter($userRepository);

                    $page           = $serviceManager
                        ->get('application')
                        ->getMvcEvent()
                        ->getRouteMatch()
                        ->getParam('page');

                    $paginator      = new Paginator($adapter);
                    $paginator->setCurrentPageNumber($page)
                        ->setItemCountPerPage(Constants::ITEMS_PER_PAGE); // how many items per page

                    return $paginator;
                }
            )

        );
    }

}
