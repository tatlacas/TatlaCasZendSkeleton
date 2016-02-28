<?php
/**
 * Created by PhpStorm.
 * User: alois - mumeraalois@gmail.com
 * Date: 10/24/2015
 * Time: 9:33 AM
 */

namespace Website\Model;
use Zend\Form\Annotation;

/**
 * @Annotation\Name("ApiFormDetails")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
 */
class ApiFormDetails
{
    /**
     * @Annotation\Required({"required":"true"})
     * @Annotation\Validator({"name":"StringLength", "options":{"min":1, "max":25}})
     * @Annotation\Validator({"name":"Regex", "options":{"pattern":"/^[a-zA-Z][a-zA-Z0-9_-]{0,24}$/", "message": "Invalid characters"}})
     * @Annotation\Attributes({"type":"text", "placeholder"="New Admin","class"="form-control"})
     * @Annotation\Options({"label":" "})
     */
    protected $apiId;

    /**
     * @Annotation\Type("Zend\Form\Element\Password")
     * @Annotation\Attributes({"autocomplete":"off", "placeholder"="Password","class"="form-control"})
     * @Annotation\Required({"required":"true" })
     * @Annotation\Options({"label":" "})
     */
    protected $password;

    /**
     * @Annotation\Type("Zend\Form\Element\Password")
     * @Annotation\Required({"required":"true" })
     * @Annotation\Attributes({"placeholder"="Repeat Password","class"="form-control"})
     * @Annotation\Options({"label":" "})
     */
    protected $confirmPassword;

    /**
     * @Annotation\Type("Zend\Form\Element\Submit")
     * @Annotation\Attributes({"value":"Create","class"="btn btn-lg btn-info btn-block"})
     */
    public $submit;


}