<?php
/**
 * Created by PhpStorm.
 * User: alois - mumeraalois@gmail.com
 * Date: 10/21/2015
 * Time: 12:32 PM
 */

namespace Application\MailMsg;


class Attachments
{
    /**
     * @var string
     */
    private $binary;

    /**
     * @var string
     */
    private $fileName;

    /**
     * @return string
     */
    public function getBinary()
    {
        return $this->binary;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @param string $binary
     * @return Attachments
     */
    public function setBinary($binary)
    {
        $this->binary = $binary;
        return $this;
    }

    /**
     * @param string $fileName
     * @return Attachments
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
        return $this;
    }


}