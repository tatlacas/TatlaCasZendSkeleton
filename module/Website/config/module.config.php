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
            'Website\Controller\Website' => 'Website\Controller\WebsiteController',
            'Website\Controller\WebDashboard' => 'Website\Controller\WebDashboardController',
        ),
    ),

    'router' => array(
        'routes' => array(
            //TODO: Also change here to match Module
            'admin' => array(
                'type'    => 'segment',
                'options' => array(
                    //TODO: Also change here to match Module

                    'route'    => '/admin[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[a-zA-Z0-9_-]*'
                    ),
                    'defaults' => array(
                        //TODO: Also change here to match Module
                        'controller'    => 'Website\Controller\Website',
                        'action'        => 'index',
                    ),
                ),

            ),

            'dashboard' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/dashboard[/:action][/page/:page][/:number]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'page'       => '[0-9]+',
                                'number'       => '[\s\S]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Website\Controller\WebDashboard',
                        'action'        => 'authenticated',
                        'number'        => '0',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                            ),
                            'defaults' => array()
                        )
                    ),

                'pagination' => array(
                    'type'    => 'Segment',
                    'options' => array(
                        'route'       => '[/:controller[/:action][/page/:page]]',
                        'constraints' => array(
                            'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'page'       => '[0-9]+',
                        ),
                        'defaults'    => array(
                        ),
                    ),

                )
    ),
            ),

        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            //TODO: Change Here to match also
            'Website' => __DIR__ . '/../view',
        ),
    ),
    'view_helper_config' => array(
        'flashmessenger' => array(
            'message_open_format'      => '<div%s><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><ul><li>',
            'message_close_string'     => '</li></ul></div>',
            'message_separator_string' => '</li><li>'
        )
    ),
    'view_helpers' => array(
        'invokables'=> array(
            'PaginationHelper' => 'Website\Model\ViewHelper\PaginationHelper'        )
    ),
);
