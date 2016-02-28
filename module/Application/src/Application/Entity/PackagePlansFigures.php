<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PackagePlansFigures
 *
 * @ORM\Table(name="package_plans_figures", indexes={@ORM\Index(name="package_plan_id", columns={"package_plan_id"})})
 * @ORM\Entity
 */
class PackagePlansFigures
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
     * @ORM\Column(name="date_effective", type="datetime", nullable=false)
     */
    private $dateEffective;

    /**
     * @var string
     *
     * @ORM\Column(name="amount", type="decimal", precision=11, scale=9, nullable=false)
     */
    private $amount;

    /**
     * @var \Application\Entity\PackagePlans
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\PackagePlans")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="package_plan_id", referencedColumnName="id")
     * })
     */
    private $packagePlan;



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
     * Set dateEffective
     *
     * @param \DateTime $dateEffective
     * @return PackagePlansFigures
     */
    public function setDateEffective($dateEffective)
    {
        $this->dateEffective = $dateEffective;

        return $this;
    }

    /**
     * Get dateEffective
     *
     * @return \DateTime 
     */
    public function getDateEffective()
    {
        return $this->dateEffective;
    }

    /**
     * Set amount
     *
     * @param string $amount
     * @return PackagePlansFigures
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
     * Set packagePlan
     *
     * @param \Application\Entity\PackagePlans $packagePlan
     * @return PackagePlansFigures
     */
    public function setPackagePlan(\Application\Entity\PackagePlans $packagePlan = null)
    {
        $this->packagePlan = $packagePlan;

        return $this;
    }

    /**
     * Get packagePlan
     *
     * @return \Application\Entity\PackagePlans 
     */
    public function getPackagePlan()
    {
        return $this->packagePlan;
    }
}
