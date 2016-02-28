<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FromNettcashServer
 *
 * @ORM\Table(name="from_nettcash_server", indexes={@ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity
 */
class FromNettcashServer
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
     * @var boolean
     *
     * @ORM\Column(name="send_state", type="boolean", nullable=false)
     */
    private $sendState = '0';

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
     * @return FromNettcashServer
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
     * @param integer $datePaid
     * @return FromNettcashServer
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
     * @return FromNettcashServer
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
     * Set sendState
     *
     * @param boolean $sendState
     * @return FromNettcashServer
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
     * Set user
     *
     * @param \Application\Entity\Users $user
     * @return FromNettcashServer
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
