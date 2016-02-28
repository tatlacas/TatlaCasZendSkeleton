<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PendingPayments
 *
 * @ORM\Table(name="pending_payments", indexes={@ORM\Index(name="user", columns={"user", "payment_type"}), @ORM\Index(name="payment_type", columns={"payment_type"}), @ORM\Index(name="IDX_E21157178D93D649", columns={"user"})})
 * @ORM\Entity
 */
class PendingPayments
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
     * @ORM\Column(name="amount", type="decimal", precision=11, scale=9, nullable=false)
     */
    private $amount;

    /**
     * @var integer
     *
     * @ORM\Column(name="date_uploaded", type="bigint", nullable=false)
     */
    private $dateUploaded;

    /**
     * @var boolean
     *
     * @ORM\Column(name="cleared", type="boolean", nullable=false)
     */
    private $cleared = '0';

    /**
     * @var boolean
     *
     * @ORM\Column(name="includes_joining_fee", type="boolean", nullable=false)
     */
    private $includesJoiningFee;

    /**
     * @var integer
     *
     * @ORM\Column(name="date_cleared", type="bigint", nullable=true)
     */
    private $dateCleared;

    /**
     * @var \Application\Entity\PaymentTypes
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\PaymentTypes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="payment_type", referencedColumnName="id")
     * })
     */
    private $paymentType;

    /**
     * @var \Application\Entity\Users
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user", referencedColumnName="user_id")
     * })
     */
    private $user;



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
     * Set amount
     *
     * @param string $amount
     * @return PendingPayments
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return string 
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set dateUploaded
     *
     * @param integer $dateUploaded
     * @return PendingPayments
     */
    public function setDateUploaded($dateUploaded)
    {
        $this->dateUploaded = $dateUploaded;

        return $this;
    }

    /**
     * Get dateUploaded
     *
     * @return integer 
     */
    public function getDateUploaded()
    {
        return $this->dateUploaded;
    }

    /**
     * Set cleared
     *
     * @param boolean $cleared
     * @return PendingPayments
     */
    public function setCleared($cleared)
    {
        $this->cleared = $cleared;

        return $this;
    }

    /**
     * Get cleared
     *
     * @return boolean 
     */
    public function getCleared()
    {
        return $this->cleared;
    }

    /**
     * Set includesJoiningFee
     *
     * @param boolean $includesJoiningFee
     * @return PendingPayments
     */
    public function setIncludesJoiningFee($includesJoiningFee)
    {
        $this->includesJoiningFee = $includesJoiningFee;

        return $this;
    }

    /**
     * Get includesJoiningFee
     *
     * @return boolean 
     */
    public function getIncludesJoiningFee()
    {
        return $this->includesJoiningFee;
    }

    /**
     * Set dateCleared
     *
     * @param integer $dateCleared
     * @return PendingPayments
     */
    public function setDateCleared($dateCleared)
    {
        $this->dateCleared = $dateCleared;

        return $this;
    }

    /**
     * Get dateCleared
     *
     * @return integer 
     */
    public function getDateCleared()
    {
        return $this->dateCleared;
    }

    /**
     * Set paymentType
     *
     * @param \Application\Entity\PaymentTypes $paymentType
     * @return PendingPayments
     */
    public function setPaymentType(\Application\Entity\PaymentTypes $paymentType = null)
    {
        $this->paymentType = $paymentType;

        return $this;
    }

    /**
     * Get paymentType
     *
     * @return \Application\Entity\PaymentTypes 
     */
    public function getPaymentType()
    {
        return $this->paymentType;
    }

    /**
     * Set user
     *
     * @param \Application\Entity\Users $user
     * @return PendingPayments
     */
    public function setUser(\Application\Entity\Users $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Application\Entity\Users 
     */
    public function getUser()
    {
        return $this->user;
    }
}
