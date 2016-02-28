<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Referrals
 *
 * @ORM\Table(name="referrals", indexes={@ORM\Index(name="referrer", columns={"referrer", "referred_phone_number"}), @ORM\Index(name="IDX_1B7DC896ED646567", columns={"referrer"})})
 * @ORM\Entity
 */
class Referrals
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
     * @var string
     *
     * @ORM\Column(name="referred_phone_number", type="string", length=50, nullable=false)
     */
    private $referredPhoneNumber;

    /**
     * @var integer
     *
     * @ORM\Column(name="date_referred", type="bigint", nullable=false)
     */
    private $dateReferred;

    /**
     * @var integer
     *
     * @ORM\Column(name="date_joined", type="bigint", nullable=true)
     */
    private $dateJoined;

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
     *   @ORM\JoinColumn(name="referrer", referencedColumnName="user_id")
     * })
     */
    private $referrer;



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
     * Set referredPhoneNumber
     *
     * @param string $referredPhoneNumber
     * @return Referrals
     */
    public function setReferredPhoneNumber($referredPhoneNumber)
    {
        $this->referredPhoneNumber = $referredPhoneNumber;

        return $this;
    }

    /**
     * Get referredPhoneNumber
     *
     * @return string 
     */
    public function getReferredPhoneNumber()
    {
        return $this->referredPhoneNumber;
    }

    /**
     * Set dateReferred
     *
     * @param integer $dateReferred
     * @return Referrals
     */
    public function setDateReferred($dateReferred)
    {
        $this->dateReferred = $dateReferred;

        return $this;
    }

    /**
     * Get dateReferred
     *
     * @return integer 
     */
    public function getDateReferred()
    {
        return $this->dateReferred;
    }

    /**
     * Set dateJoined
     *
     * @param integer $dateJoined
     * @return Referrals
     */
    public function setDateJoined($dateJoined)
    {
        $this->dateJoined = $dateJoined;

        return $this;
    }

    /**
     * Get dateJoined
     *
     * @return integer 
     */
    public function getDateJoined()
    {
        return $this->dateJoined;
    }

    /**
     * Set paidOut
     *
     * @param boolean $paidOut
     * @return Referrals
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
     * Set referrer
     *
     * @param \Application\Entity\Users $referrer
     * @return Referrals
     */
    public function setReferrer(\Application\Entity\Users $referrer = null)
    {
        $this->referrer = $referrer;

        return $this;
    }

    /**
     * Get referrer
     *
     * @return \Application\Entity\Users 
     */
    public function getReferrer()
    {
        return $this->referrer;
    }
}
