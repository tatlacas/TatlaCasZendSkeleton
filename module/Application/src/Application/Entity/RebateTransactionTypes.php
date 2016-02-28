<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RebateTransactionTypes
 *
 * @ORM\Table(name="rebate_transaction_types")
 * @ORM\Entity
 */
class RebateTransactionTypes
{
    /**
     * @var integer
     *
     * @ORM\Column(name="transaction_type_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $transactionTypeId;

    /**
     * @var string
     *
     * @ORM\Column(name="transaction_description", type="string", length=150, nullable=false)
     */
    private $transactionDescription;



    /**
     * Get transactionTypeId
     *
     * @return integer 
     */
    public function getTransactionTypeId()
    {
        return $this->transactionTypeId;
    }

    /**
     * Set transactionDescription
     *
     * @param string $transactionDescription
     * @return RebateTransactionTypes
     */
    public function setTransactionDescription($transactionDescription)
    {
        $this->transactionDescription = $transactionDescription;

        return $this;
    }

    /**
     * Get transactionDescription
     *
     * @return string 
     */
    public function getTransactionDescription()
    {
        return $this->transactionDescription;
    }
}
