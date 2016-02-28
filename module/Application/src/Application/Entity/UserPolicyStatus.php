<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserPolicyStatus
 *
 * @ORM\Table(name="user_policy_status", uniqueConstraints={@ORM\UniqueConstraint(name="user_policy_status_id_UNIQUE", columns={"user_policy_status_id"})})
 * @ORM\Entity
 */
class UserPolicyStatus
{
    /**
     * @var integer
     *
     * @ORM\Column(name="user_policy_status_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $userPolicyStatusId;

    /**
     * @var string
     *
     * @ORM\Column(name="status_description", type="string", length=45, nullable=false)
     */
    private $statusDescription;



    /**
     * Get userPolicyStatusId
     *
     * @return integer 
     */
    public function getUserPolicyStatusId()
    {
        return $this->userPolicyStatusId;
    }

    /**
     * Set statusDescription
     *
     * @param string $statusDescription
     * @return UserPolicyStatus
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
}
