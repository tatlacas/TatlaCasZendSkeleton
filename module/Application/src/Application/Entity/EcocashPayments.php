<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EcocashPayments
 *
 * @ORM\Table(name="ecocash_payments", indexes={@ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity
 */
class EcocashPayments
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
     * @ORM\Column(name="amount_paid", type="decimal", precision=11, scale=9, nullable=false)
     */
    private $amountPaid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_paid", type="datetime", nullable=false)
     */
    private $datePaid;

    /**
     * @var string
     *
     * @ORM\Column(name="reference_id", type="string", length=20, nullable=true)
     */
    private $referenceId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="send_state", type="boolean", nullable=false)
     */
    private $sendState = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="excess_amount", type="decimal", precision=11, scale=9, nullable=true)
     */
    private $excessAmount;

    /**
     * @var \Application\Entity\Users
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
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
     * Set amountPaid
     *
     * @param string $amountPaid
     * @return EcocashPayments
     */
    public function setAmountPaid($amountPaid)
    {
        $this->amountPaid = $amountPaid;

        return $this;
    }

    /**
     * Get amountPaid
     *
     * @return string 
     */
    public function getAmountPaid()
    {
        return $this->amountPaid;
    }

    /**
     * Set datePaid
     *
     * @param \DateTime $datePaid
     * @return EcocashPayments
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
     * Set referenceId
     *
     * @param string $referenceId
     * @return EcocashPayments
     */
    public function setReferenceId($referenceId)
    {
        $this->referenceId = $referenceId;

        return $this;
    }

    /**
     * Get referenceId
     *
     * @return string 
     */
    public function getReferenceId()
    {
        return $this->referenceId;
    }

    /**
     * Set sendState
     *
     * @param boolean $sendState
     * @return EcocashPayments
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
     * Set excessAmount
     *
     * @param string $excessAmount
     * @return EcocashPayments
     */
    public function setExcessAmount($excessAmount)
    {
        $this->excessAmount = $excessAmount;

        return $this;
    }

    /**
     * Get excessAmount
     *
     * @return string 
     */
    public function getExcessAmount()
    {
        return $this->excessAmount;
    }

    /**
     * Set user
     *
     * @param \Application\Entity\Users $user
     * @return EcocashPayments
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
