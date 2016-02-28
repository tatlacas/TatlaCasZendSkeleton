<?php
/**
 * Created by PhpStorm.
 * User: alois
 * Date: 9/9/2015
 * Time: 9:58 AM
 */

namespace Mobile\Model;


use Application\Entity\ClustersPayments;
use Application\Model\Constants;
use Application\Model\DoctrineInitialization;

class TestRegistration extends DoctrineInitialization
{
    function __construct($service_locator)
    {
        parent::__construct($service_locator);
    }

    public function testregisterUser()
    {
        if (true) {
            //dummy result
            $info = null;
            $info[0] = array('fname' => 'Tatenda Caston',
                'sname' => 'Hove',
                'state' => '2',
                'user_package_amount' => '$12.50',
                'package' => '100',
                'yearmd' => '2015-09-30',
            );
            $info[1] = array('fname' => 'Tatenda Caston',
                'sname' => 'Hove',
                'state' => '2',
                'user_package_amount' => '$12.50',
                'package' => '100',
                'yearmd' => '2015-10-30',
            );
            $info[2] = array('fname' => 'Ropafadzo',
                'sname' => 'Magwali',
                'state' => '1',
                'user_package_amount' => '$3',
                'package' => '100',
                'yearmd' => '2015-10-30',
            );
            $info[3] = array('fname' => 'Tatenda Caston',
                'sname' => 'Hove',
                'state' => '3',
                'user_package_amount' => '$7.50',
                'package' => '100',
                'yearmd' => '2015-10-30',
            );
            $info[4] = array('fname' => 'Tatenda Caston',
                'sname' => 'Hove',
                'state' => '2',
                'user_package_amount' => '$12.50',
                'package' => '100',
                'yearmd' => '2015-11-30',
            );
            $info[5] = array('fname' => 'Ropafadzo',
                'sname' => 'Magwali',
                'state' => '1',
                'user_package_amount' => '$3',
                'package' => '100',
                'yearmd' => '2015-11-30',
            );
            $info[6] = array('fname' => 'Tatenda Caston',
                'sname' => 'Hove',
                'state' => '3',
                'user_package_amount' => '$7.50',
                'package' => '100',
                'yearmd' => '2015-11-30',
            );
            $xml_output = "";

            foreach ($info as $rec) {
                $xml_output .= '<to_pay fname = "' . $rec['fname'] . '" sname ="' . $rec['sname'] . '" is_dep ="' . $rec['state'] . '" amount ="' . $rec['user_package_amount'] . '" server_id ="' . $rec['package'] .
                    '" owing_date ="' . $rec['yearmd'] . '" />';
            }
            die($xml_output);
        }

    }


}