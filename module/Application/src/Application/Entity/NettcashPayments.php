<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NettcashPayments
 *
 * @ORM\Table(name="nettcash_payments", indexes={@ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity
 */
class NettcashPayments
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
     * @ORM\Column(name="date_paid", type="bigint", nullable=false)
     */
    private $datePaid;

    /**
     * @var string
     *
     * @ORM\Column(name="transaction_id", type="string", length=20, nullable=false)
     */
    private $transactionId;

    /**
     * @var string
     *
     * @ORM\Column(name="excess_amount", type="decimal", precision=11, scale=9, nullable=true)
     */
    private $excessAmount = '0.000000000';

    /**
     * @var string
     *
     * @ORM\Column(name="amount_paid", type="decimal", precision=11, scale=9, nullable=true)
     */
    private $amountPaid;

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
     * Set datePaid
     *
     * @param integer $datePaid
     * @return NettcashPayments
     */
    public function setDatePaid($datePaid)
    {
        $this->datePaid = $datePaid;

        return $this;
    }

    /**
     * Get datePaid
     *
     * @return integer 
     */
    public function getDatePaid()
    {
        return $this->datePaid;
    }

    /**
     * Set transactionId
     *
     * @param string $transactionId
     * @return NettcashPayments
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    /**
     * Get transactionId
     *
     * @return string 
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * Set excessAmount
     *
     * @param string $excessAmount
     * @return NettcashPayments
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
     * Set amountPaid
     *
     * @param string $amountPaid
     * @return NettcashPayments
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
     * Set user
     *
     * @param \Application\Entity\Users $user
     * @return NettcashPayments
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
