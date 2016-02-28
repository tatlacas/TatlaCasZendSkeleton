<?php
/**
 * Created by PhpStorm.
 * User: alois - mumeraalois@gmail.com
 * Date: 10/24/2015
 * Time: 11:06 AM
 */

namespace Website\Model;

use Zend\Form\Annotation;

/**
 * @Annotation\Name("ChangePasswordFormAnnotation")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
 */
class ChangePasswordFormAnnotation
{
    /**
     * @Annotation\Required({"required":"true"})
     * @Annotation\Validator({"name":"StringLength", "options":{"min":1, "max":25}})
     * @Annotation\Validator({"name":"Regex", "options":{"pattern":"/^[a-zA-Z][a-zA-Z0-9_-]{0,24}$/", "message": "Invalid characters"}})
     * @Annotation\Attributes({"type":"text", "placeholder"="Admin Username","class"="form-control"})
     * @Annotation\Options({"label":" "})
     */
    protected $adminName;
    /**
     * @Annotation\Type("Zend\Form\Element\Password")
     * @Annotation\Attributes({"autocomplete":"off", "placeholder"="New Password","class"="form-control"})
     * @Annotation\Required({"required":"true" })
     * @Annotation\Options({"label":" "})
     */
    protected $newPassword;

    /**
     * @Annotation\Type("Zend\Form\Element\Password")
     * @Annotation\Required({"required":"true" })
     * @Annotation\Attributes({"placeholder"="Repeat Password","class"="form-control"})
     * @Annotation\Options({"label":" "})
     */
    protected $confirmNewPassword;

    /**
     * @Annotation\Type("Zend\Form\Element\Submit")
     * @Annotation\Attributes({"value":"Change Password","class"="btn btn-lg btn-info btn-block"})
     */
    public $submit;

}