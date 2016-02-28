<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RebateTransactions
 *
 * @ORM\Table(name="rebate_transactions", indexes={@ORM\Index(name="rebate_transactions_ibfk_1_idx", columns={"user_id"}), @ORM\Index(name="rebate_transactions_ibfk_2_idx", columns={"transaction_type_id"}), @ORM\Index(name="rebate_transactions_ibfk_3_idx", columns={"rebate_month_id"})})
 * @ORM\Entity
 */
class RebateTransactions
{
    /**
     * @var integer
     *
     * @ORM\Column(name="transaction_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $transactionId;

    /**
     * @var string
     *
     * @ORM\Column(name="rebate_amount", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $rebateAmount = '0.00';

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
     * @var \Application\Entity\RebateTransactionTypes
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\RebateTransactionTypes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="transaction_type_id", referencedColumnName="transaction_type_id")
     * })
     */
    private $transactionType;

    /**
     * @var \Application\Entity\RebateMonth
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\RebateMonth")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="rebate_month_id", referencedColumnName="rebate_month_id")
     * })
     */
    private $rebateMonth;



    /**
     * Get transactionId
     *
     * @return integer 
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * Set rebateAmount
     *
     * @param string $rebateAmount
     * @return RebateTransactions
     */
    public function setRebateAmount($rebateAmount)
    {
        $this->rebateAmount = $rebateAmount;

        return $this;
    }

    /**
     * Get rebateAmount
     *
     * @return string 
     */
    public function getRebateAmount()
    {
        return $this->rebateAmount;
    }

    /**
     * Set user
     *
     * @param \Application\Entity\Users $user
     * @return RebateTransactions
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

    /**
     * Set transactionType
     *
     * @param \Application\Entity\RebateTransactionTypes $transactionType
     * @return RebateTransactions
     */
    public function setTransactionType(\Application\Entity\RebateTransactionTypes $transactionType = null)
    {
        $this->transactionType = $transactionType;

        return $this;
    }

    /**
     * Get transactionType
     *
     * @return \Application\Entity\RebateTransactionTypes 
     */
    public function getTransactionType()
    {
        return $this->transactionType;
    }

    /**
     * Set rebateMonth
     *
     * @param \Application\Entity\RebateMonth $rebateMonth
     * @return RebateTransactions
     */
    public function setRebateMonth(\Application\Entity\RebateMonth $rebateMonth = null)
    {
        $this->rebateMonth = $rebateMonth;

        return $this;
    }

    /**
     * Get rebateMonth
     *
     * @return \Application\Entity\RebateMonth 
     */
    public function getRebateMonth()
    {
        return $this->rebateMonth;
    }
}
