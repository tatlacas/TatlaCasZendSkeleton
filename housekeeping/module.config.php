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
            'Students\Controller\Students' => 'Students\Controller\StudentsController',
        ),
    ),
    'router' => array(
        'routes' => array(
            //TODO: Also change here to match Module
            'student' => array(
                'type'    => 'segment',
                'options' => array(
                    //TODO: Also change here to match Module

                    'route'    => '/student[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[a-zA-Z0-9_-]*'
                    ),
                    'defaults' => array(
                        //TODO: Also change here to match Module
                        'controller'    => 'Students\Controller\Students',
                        'action'        => 'index',
                    ),
                ),

            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            //TODO: Chagen Here to match also
            'Students' => __DIR__ . '/../view',
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
