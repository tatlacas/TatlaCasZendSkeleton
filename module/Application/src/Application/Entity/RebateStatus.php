<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RebateStatus
 *
 * @ORM\Table(name="rebate_status")
 * @ORM\Entity
 */
class RebateStatus
{
    /**
     * @var integer
     *
     * @ORM\Column(name="status_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $statusId;

    /**
     * @var string
     *
     * @ORM\Column(name="status_description", type="string", length=50, nullable=false)
     */
    private $statusDescription;

    /**
     * @var boolean
     *
     * @ORM\Column(name="paid_for_month", type="boolean", nullable=false)
     */
    private $paidForMonth = '0';



    /**
     * Get statusId
     *
     * @return integer 
     */
    public function getStatusId()
    {
        return $this->statusId;
    }

    /**
     * Set statusDescription
     *
     * @param string $statusDescription
     * @return RebateStatus
     */
    public function setStatusDescription($statusDescription)
    {
        $this->statusDescription = $statusDescription;

        return $this;
    }

    /**
     * Get statusDescription
     *
     * @return string 
     */
    public function getStatusDescription()
    {
        return $this->statusDescription;
    }

    /**
     * Set paidForMonth
     *
     * @param boolean $paidForMonth
     * @return RebateStatus
     */
    public function setPaidForMonth($paidForMonth)
    {
        $this->paidForMonth = $paidForMonth;

        return $this;
    }

    /**
     * Get paidForMonth
     *
     * @return boolean 
     */
    public function getPaidForMonth()
    {
        return $this->paidForMonth;
    }
}
