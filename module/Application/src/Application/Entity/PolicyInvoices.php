<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PolicyInvoices
 *
 * @ORM\Table(name="policy_invoices", indexes={@ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity
 */
class PolicyInvoices
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
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="amount_owing", type="decimal", precision=11, scale=9, nullable=false)
     */
    private $amountOwing;

    /**
     * @var string
     *
     * @ORM\Column(name="datetime", type="string", length=20, nullable=false)
     */
    private $datetime;



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
     * Set userId
     *
     * @param integer $userId
     * @return PolicyInvoices
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set amountOwing
     *
     * @param string $amountOwing
     * @return PolicyInvoices
     */
    public function setAmountOwing($amountOwing)
    {
        $this->amountOwing = $amountOwing;

        return $this;
    }

    /**
     * Get amountOwing
     *
     * @return string 
     */
    public function getAmountOwing()
    {
        return $this->amountOwing;
    }

    /**
     * Set datetime
     *
     * @param string $datetime
     * @return PolicyInvoices
     */
    public function setDatetime($datetime)
    {
        $this->datetime = $datetime;

        return $this;
    }

    /**
     * Get datetime
     *
     * @return string 
     */
    public function getDatetime()
    {
        return $this->datetime;
    }
}
