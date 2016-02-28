<?php
/**
 * Created by PhpStorm.
 * User: alois - mumeraalois@gmail.com
 * Date: 10/24/2015
 * Time: 2:11 PM
 */

namespace Website\Model;

use Zend\Form\Annotation;

/**
 * @Annotation\Name("NewsFormDetails")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
 */
class NewsFormDetails
{
    /**
     * @Annotation\Required({"required":"true"})
     * @Annotation\Validator({"name":"StringLength", "options":{"min":1, "max":25}})
     * @Annotation\Validator({"name":"Regex", "options":{"pattern":"/^[a-zA-Z][a-zA-Z0-9_-]{0,24}$/", "message": "Invalid characters"}})
     * @Annotation\Attributes({"type":"text", "placeholder"="Title","class"="form-control"})
     * @Annotation\Options({"label":" "})
     */
    protected $title;

    /**
     * @Annotation\Type("Zend\Form\Element\TextArea")
     * @Annotation\Required({"required":"true"})
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringToUpper"})
     * @Annotation\Validator({"name":"StringLength", "options":{"min":"15","max":160}})
     * @Annotation\Attributes({"placeholder"="Message","class"="form-control","rows"="6"})
     * @Annotation\Options({"label":" "})
     */
    public $message;

    /**
     * @Annotation\Type("Zend\Form\Element\Submit")
     * @Annotation\Attributes({"value":"Send Message","class"="btn btn-lg btn-info btn-block"})
     */
    public $submit;


}