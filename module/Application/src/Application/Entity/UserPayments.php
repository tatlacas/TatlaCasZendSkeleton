<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserPayments
 *
 * @ORM\Table(name="user_payments", indexes={@ORM\Index(name="payee", columns={"payee"}), @ORM\Index(name="user_policy_id", columns={"subscribed_package_id"}), @ORM\Index(name="payment_type", columns={"payment_type"})})
 * @ORM\Entity
 */
class UserPayments
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
     * @var \DateTime
     *
     * @ORM\Column(name="month_paid_for", type="datetime", nullable=false)
     */
    private $monthPaidFor;

    /**
     * @var string
     *
     * @ORM\Column(name="external_ref", type="string", length=20, nullable=false)
     */
    private $externalRef;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_paid", type="datetime", nullable=false)
     */
    private $datePaid;

    /**
     * @var boolean
     *
     * @ORM\Column(name="send_state", type="boolean", nullable=false)
     */
    private $sendState;

    /**
     * @var \Application\Entity\Users
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="payee", referencedColumnName="user_id")
     * })
     */
    private $payee;

    /**
     * @var \Application\Entity\SubscribedPackages
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\SubscribedPackages")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="subscribed_package_id", referencedColumnName="id")
     * })
     */
    private $subscribedPackage;

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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set monthPaidFor
     *
     * @param \DateTime $monthPaidFor
     * @return UserPayments
     */
    public function setMonthPaidFor($monthPaidFor)
    {
        $this->monthPaidFor = $monthPaidFor;

        return $this;
    }

    /**
     * Get monthPaidFor
     *
     * @return \DateTime 
     */
    public function getMonthPaidFor()
    {
        return $this->monthPaidFor;
    }

    /**
     * Set externalRef
     *
     * @param string $externalRef
     * @return UserPayments
     */
    public function setExternalRef($externalRef)
    {
        $this->externalRef = $externalRef;

        return $this;
    }

    /**
     * Get externalRef
     *
     * @return string 
     */
    public function getExternalRef()
    {
        return $this->externalRef;
    }

    /**
     * Set datePaid
     *
     * @param \DateTime $datePaid
     * @return UserPayments
     */
    public function setDatePaid($datePaid)
    {
        $this->datePaid = $datePaid;

        return $this;
    }

    /**
     * Get datePaid
     *
     * @return \DateTime 
     */
    public function getDatePaid()
    {
        return $this->datePaid;
    }

    /**
     * Set sendState
     *
     * @param boolean $sendState
     * @return UserPayments
     */
    public function setSendState($sendState)
    {
        $this->sendState = $sendState;

        return $this;
    }

    /**
     * Get sendState
     *
     * @return boolean 
     */
    public function getSendState()
    {
        return $this->sendState;
    }

    /**
     * Set payee
     *
     * @param \Application\Entity\Users $payee
     * @return UserPayments
     */
    public function setPayee(\Application\Entity\Users $payee = null)
    {
        $this->payee = $payee;

        return $this;
    }

    /**
     * Get payee
     *
     * @return \Application\Entity\Users 
     */
    public function getPayee()
    {
        return $this->payee;
    }

    /**
     * Set subscribedPackage
     *
     * @param \Application\Entity\SubscribedPackages $subscribedPackage
     * @return UserPayments
     */
    public function setSubscribedPackage(\Application\Entity\SubscribedPackages $subscribedPackage = null)
    {
        $this->subscribedPackage = $subscribedPackage;

        return $this;
    }

    /**
     * Get subscribedPackage
     *
     * @return \Application\Entity\SubscribedPackages 
     */
    public function getSubscribedPackage()
    {
        return $this->subscribedPackage;
    }

    /**
     * Set paymentType
     *
     * @param \Application\Entity\PaymentTypes $paymentType
     * @return UserPayments
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
}
