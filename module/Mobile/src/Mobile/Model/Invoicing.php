<?php
/**
 * Created by PhpStorm.
 * User: alois - mumeraalois@gmail.com
 * Date: 9/29/2015
 * Time: 8:49 AM
 */

namespace Mobile\Model;


use Application\Model\DoctrineInitialization;

class Invoicing  extends  DoctrineInitialization
{

    function __construct($service_locator)
    {
        parent::__construct($service_locator);
    }

public  function checkPayments($url){

}


}