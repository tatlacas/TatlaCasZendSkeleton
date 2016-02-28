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
            'User\Controller\Authentication' => 'User\Controller\AuthenticationController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'user' => array(
                'type'    => 'segment',
                'options' => array(

                    'route'    => '/user[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+'
                    ),
                    'defaults' => array(
                        //TODO: Also change here to match Module
                        'controller'    => 'User\Controller\Authentication',
                        'action'        => 'index',
                    ),
                ),

            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'User' => __DIR__ . '/../view',
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
