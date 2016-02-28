<?php
namespace Website\action_Forms;
use Zend\Form\Form;

/**
 * Created by PhpStorm.
 * User: alois - mumeraalois@gmail.com
 * Date: 10/20/2015
 * Time: 8:28 AM
 */
class loginForm extends Form
{
    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->add(array(
            'name' => 'name',
            'options' => array(
                'label' => 'User name',
            ),
            'type'  => 'Text',
        ));

        $this->add(array(
            'name' => 'send',
            'type'  => 'Submit',
            'attributes' => array(
                'value' => 'Sign In',
            ),
        ));

    }

}