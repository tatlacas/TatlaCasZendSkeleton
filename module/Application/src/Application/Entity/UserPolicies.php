<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserPolicies
 *
 * @ORM\Table(name="user_policies", indexes={@ORM\Index(name="user_id", columns={"user_id", "package_plan_id"}), @ORM\Index(name="package_plan_id", columns={"package_plan_id"}), @ORM\Index(name="user_policies_ibfk_3_idx", columns={"policy_status_id"}), @ORM\Index(name="IDX_65BCEA76ED395", columns={"user_id"})})
 * @ORM\Entity
 */
class UserPolicies
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
     * @ORM\Column(name="policy_number", type="string", length=30, nullable=true)
     */
    private $policyNumber;

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
     * @var \Application\Entity\UserPolicyStatus
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\UserPolicyStatus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="policy_status_id", referencedColumnName="user_policy_status_id")
     * })
     */
    private $policyStatus;



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
     * Set policyNumber
     *
     * @param string $policyNumber
     * @return UserPolicies
     */
    public function setPolicyNumber($policyNumber)
    {
        $this->policyNumber = $policyNumber;

        return $this;
    }

    /**
     * Get policyNumber
     *
     * @return string 
     */
    public function getPolicyNumber()
    {
        return $this->policyNumber;
    }

    /**
     * Set user
     *
     * @param \Application\Entity\Users $user
     * @return UserPolicies
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
     * @return UserPolicies
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
     * Set policyStatus
     *
     * @param \Application\Entity\UserPolicyStatus $policyStatus
     * @return UserPolicies
     */
    public function setPolicyStatus(\Application\Entity\UserPolicyStatus $policyStatus = null)
    {
        $this->policyStatus = $policyStatus;

        return $this;
    }

    /**
     * Get policyStatus
     *
     * @return \Application\Entity\UserPolicyStatus 
     */
    public function getPolicyStatus()
    {
        return $this->policyStatus;
    }
}
