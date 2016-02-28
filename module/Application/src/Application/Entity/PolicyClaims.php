<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PolicyClaims
 *
 * @ORM\Table(name="policy_claims", indexes={@ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity
 */
class PolicyClaims
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
     * @ORM\Column(name="description", type="string", length=100, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="claim_date", type="string", length=20, nullable=false)
     */
    private $claimDate;



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
     * @return PolicyClaims
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
     * Set description
     *
     * @param string $description
     * @return PolicyClaims
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set claimDate
     *
     * @param string $claimDate
     * @return PolicyClaims
     */
    public function setClaimDate($claimDate)
    {
        $this->claimDate = $claimDate;

        return $this;
    }

    /**
     * Get claimDate
     *
     * @return string 
     */
    public function getClaimDate()
    {
        return $this->claimDate;
    }
}
