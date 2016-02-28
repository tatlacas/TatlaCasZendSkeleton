<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PackagePlans
 *
 * @ORM\Table(name="package_plans")
 * @ORM\Entity
 */
class PackagePlans
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
     * @ORM\Column(name="plan_name", type="string", length=120, nullable=false)
     */
    private $planName;

    /**
     * @var string
     *
     * @ORM\Column(name="plan_description", type="string", length=500, nullable=true)
     */
    private $planDescription;



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
     * Set planName
     *
     * @param string $planName
     * @return PackagePlans
     */
    public function setPlanName($planName)
    {
        $this->planName = $planName;

        return $this;
    }

    /**
     * Get planName
     *
     * @return string 
     */
    public function getPlanName()
    {
        return $this->planName;
    }

    /**
     * Set planDescription
     *
     * @param string $planDescription
     * @return PackagePlans
     */
    public function setPlanDescription($planDescription)
    {
        $this->planDescription = $planDescription;

        return $this;
    }

    /**
     * Get planDescription
     *
     * @return string 
     */
    public function getPlanDescription()
    {
        return $this->planDescription;
    }
}
