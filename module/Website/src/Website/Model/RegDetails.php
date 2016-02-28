<?php
/**
 * Created by PhpStorm.
 * User: alois - mumeraalois@gmail.com
 * Date: 10/23/2015
 * Time: 10:00 AM
 */

namespace Website\Model;
use Zend\Form\Annotation;

/**
 * @Annotation\Name("RegDetails")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
 */
class RegDetails
{
    /**
     * @Annotation\Exclude()
     */
    protected $id;

    /**
     * @Annotation\Required({"required":"true"})
     * @Annotation\Validator({"name":"StringLength", "options":{"min":1, "max":25}})
     * @Annotation\Validator({"name":"Regex", "options":{"pattern":"/^[a-zA-Z][a-zA-Z0-9_-]{0,24}$/", "message": "Invalid characters"}})
     * @Annotation\Attributes({"type":"text", "placeholder"="Username"})
     * @Annotation\Options({"label":" "})
     */
    protected $username;

    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Required({"required":"true"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"EmailAddress"})
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Attributes({"autocomplete":"off", "placeholder"="User's Email","class"="form-control"})
     * @Annotation\Options({"label":" "})
     */
    protected $email;

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
     * @Annotation\Attributes({"value":"Register","class"="btn btn-lg btn-primary btn-block"})
     */
    public $submit;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUserName()
    {
        return $this->UserName;
    }

    /**
     * @param mixed $UserName
     */
    public function setUserName($UserName)
    {
        $this->UserName = $UserName;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->Email;
    }

    /**
     * @param mixed $Email
     */
    public function setEmail($Email)
    {
        $this->Email = $Email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->Password;
    }

    /**
     * @param mixed $Password
     */
    public function setPassword($Password)
    {
        $this->Password = $Password;
    }

    /**
     * @return mixed
     */
    public function getConfirmPassword()
    {
        return $this->ConfirmPassword;
    }

    /**
     * @param mixed $ConfirmPassword
     */
    public function setConfirmPassword($ConfirmPassword)
    {
        $this->ConfirmPassword = $ConfirmPassword;
    }


}