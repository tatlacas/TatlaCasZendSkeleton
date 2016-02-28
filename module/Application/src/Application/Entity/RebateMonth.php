<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RebateMonth
 *
 * @ORM\Table(name="rebate_month")
 * @ORM\Entity
 */
class RebateMonth
{
    /**
     * @var integer
     *
     * @ORM\Column(name="rebate_month_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $rebateMonthId;

    /**
     * @var integer
     *
     * @ORM\Column(name="year", type="integer", nullable=false)
     */
    private $year;

    /**
     * @var integer
     *
     * @ORM\Column(name="month", type="integer", nullable=false)
     */
    private $month;

    /**
     * @var boolean
     *
     * @ORM\Column(name="processed", type="boolean", nullable=false)
     */
    private $processed = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_processed", type="datetime", nullable=true)
     */
    private $dateProcessed;



    /**
     * Get rebateMonthId
     *
     * @return integer 
     */
    public function getRebateMonthId()
    {
        return $this->rebateMonthId;
    }

    /**
     * Set year
     *
     * @param integer $year
     * @return RebateMonth
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return integer 
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set month
     *
     * @param integer $month
     * @return RebateMonth
     */
    public function setMonth($month)
    {
        $this->month = $month;

        return $this;
    }

    /**
     * Get month
     *
     * @return integer 
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Set processed
     *
     * @param boolean $processed
     * @return RebateMonth
     */
    public function setProcessed($processed)
    {
        $this->processed = $processed;

        return $this;
    }

    /**
     * Get processed
     *
     * @return boolean 
     */
    public function getProcessed()
    {
        return $this->processed;
    }

    /**
     * Set dateProcessed
     *
     * @param \DateTime $dateProcessed
     * @return RebateMonth
     */
    public function setDateProcessed($dateProcessed)
    {
        $this->dateProcessed = $dateProcessed;

        return $this;
    }

    /**
     * Get dateProcessed
     *
     * @return \DateTime 
     */
    public function getDateProcessed()
    {
        return $this->dateProcessed;
    }
}
