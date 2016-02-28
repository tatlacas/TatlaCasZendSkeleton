<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SubscribedPackages
 *
 * @ORM\Table(name="subscribed_packages", indexes={@ORM\Index(name="user_id", columns={"user_id", "package_plan_id"}), @ORM\Index(name="package_plan_id", columns={"package_plan_id"}), @ORM\Index(name="dependent_id", columns={"dependent_id"}), @ORM\Index(name="IDX_C2CBAE37A76ED395", columns={"user_id"})})
 * @ORM\Entity
 */
class SubscribedPackages
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
     * @ORM\Column(name="date_activated", type="datetime", nullable=false)
     */
    private $dateActivated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_deactivated", type="datetime", nullable=true)
     */
    private $dateDeactivated;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_dependent", type="boolean", nullable=false)
     */
    private $isDependent = '0';

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
     * @var \Application\Entity\PackagePlans
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\PackagePlans")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="package_plan_id", referencedColumnName="id")
     * })
     */
    private $packagePlan;

    /**
     * @var \Application\Entity\UserDependents
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\UserDependents")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="dependent_id", referencedColumnName="id")
     * })
     */
    private $dependent;



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
     * Set dateActivated
     *
     * @param \DateTime $dateActivated
     * @return SubscribedPackages
     */
    public function setDateActivated($dateActivated)
    {
        $this->dateActivated = $dateActivated;

        return $this;
    }

    /**
     * Get dateActivated
     *
     * @return \DateTime 
     */
    public function getDateActivated()
    {
        return $this->dateActivated;
    }

    /**
     * Set dateDeactivated
     *
     * @param \DateTime $dateDeactivated
     * @return SubscribedPackages
     */
    public function setDateDeactivated($dateDeactivated)
    {
        $this->dateDeactivated = $dateDeactivated;

        return $this;
    }

    /**
     * Get dateDeactivated
     *
     * @return \DateTime 
     */
    public function getDateDeactivated()
    {
        return $this->dateDeactivated;
    }

    /**
     * Set status
     *
     * @param boolean $status
     * @return SubscribedPackages
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set isDependent
     *
     * @param boolean $isDependent
     * @return SubscribedPackages
     */
    public function setIsDependent($isDependent)
    {
        $this->isDependent = $isDependent;

        return $this;
    }

    /**
     * Get isDependent
     *
     * @return boolean 
     */
    public function getIsDependent()
    {
        return $this->isDependent;
    }

    /**
     * Set user
     *
     * @param \Application\Entity\Users $user
     * @return SubscribedPackages
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
     * Set packagePlan
     *
     * @param \Application\Entity\PackagePlans $packagePlan
     * @return SubscribedPackages
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

    /**
     * Set dependent
     *
     * @param \Application\Entity\UserDependents $dependent
     * @return SubscribedPackages
     */
    public function setDependent(\Application\Entity\UserDependents $dependent = null)
    {
        $this->dependent = $dependent;

        return $this;
    }

    /**
     * Get dependent
     *
     * @return \Application\Entity\UserDependents 
     */
    public function getDependent()
    {
        return $this->dependent;
    }
}
