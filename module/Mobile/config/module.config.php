<?php
/**
 * TatlaCas Customized
 *
 *
 * @copyright Copyright (c) 20013-2014 Fundamental Technologies (Private) Limited (http://www.fundamentaltechno.com)
 * @author   Tatenda Caston Hove <tathove@gmail.com>
 *
 */
return array(
    'controllers' => array(
        'invokables' => array(
            //TODO:  Change Here to match Module
            'Mobile\Controller\Main' => 'Mobile\Controller\MainController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'mobile' => array(
                'type'    => 'segment',
                'options' => array(

                    'route'    => '/mobile[/:action][/:id][/:id1]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[a-zA-Z0-9_-]*',
                        'id1'     => '[\s\S]*'
                    ),
                    'defaults' => array(
                        //TODO: Also change here to match Module
                        'controller'    => 'Mobile\Controller\Main',
                        'action'        => 'index',
                    ),
                ),

            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'Mobile' => __DIR__ . '/../view',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            //TODO May change Here Too
            'Sidebar' => 'User\View\Helper\Sidebar',
            'Dashboard' => 'User\View\Helper\Dashboard',
        ),
    )

);
