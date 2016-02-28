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
    
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host'     => 'localhost',
                    'port'     => '3306',
                    'dbname'   => 'shiri',
                )
            )
        )
    )
);