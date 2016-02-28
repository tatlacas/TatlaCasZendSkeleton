<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserCapturer
 *
 * @ORM\Table(name="user_capturer", indexes={@ORM\Index(name="capturer", columns={"capturer", "captured_user"}), @ORM\Index(name="captured_user", columns={"captured_user"}), @ORM\Index(name="IDX_5C6FF3CCBCE217CC", columns={"capturer"})})
 * @ORM\Entity
 */
class UserCapturer
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="date_captured", type="bigint", nullable=false)
     */
    private $dateCaptured;

    /**
     * @var boolean
     *
     * @ORM\Column(name="paid_out", type="boolean", nullable=false)
     */
    private $paidOut = '0';

    /**
     * @var \Application\Entity\Users
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="capturer", referencedColumnName="user_id")
     * })
     */
    private $capturer;

    /**
     * @var \Application\Entity\Users
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="captured_user", referencedColumnName="user_id")
     * })
     */
    private $capturedUser;



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dateCaptured
     *
     * @param integer $dateCaptured
     * @return UserCapturer
     */
    public function setDateCaptured($dateCaptured)
    {
        $this->dateCaptured = $dateCaptured;

        return $this;
    }

    /**
     * Get dateCaptured
     *
     * @return integer 
     */
    public function getDateCaptured()
    {
        return $this->dateCaptured;
    }

    /**
     * Set paidOut
     *
     * @param boolean $paidOut
     * @return UserCapturer
     */
    public function setPaidOut($paidOut)
    {
        $this->paidOut = $paidOut;

        return $this;
    }

    /**
     * Get paidOut
     *
     * @return boolean 
     */
    public function getPaidOut()
    {
        return $this->paidOut;
    }

    /**
     * Set capturer
     *
     * @param \Application\Entity\Users $capturer
     * @return UserCapturer
     */
    public function setCapturer(\Application\Entity\Users $capturer = null)
    {
        $this->capturer = $capturer;

        return $this;
    }

    /**
     * Get capturer
     *
     * @return \Application\Entity\Users 
     */
    public function getCapturer()
    {
        return $this->capturer;
    }

    /**
     * Set capturedUser
     *
     * @param \Application\Entity\Users $capturedUser
     * @return UserCapturer
     */
    public function setCapturedUser(\Application\Entity\Users $capturedUser = null)
    {
        $this->capturedUser = $capturedUser;

        return $this;
    }

    /**
     * Get capturedUser
     *
     * @return \Application\Entity\Users 
     */
    public function getCapturedUser()
    {
        return $this->capturedUser;
    }
}
