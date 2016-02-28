<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Branches
 *
 * @ORM\Table(name="branches", uniqueConstraints={@ORM\UniqueConstraint(name="branch_id", columns={"branch_id"})})
 * @ORM\Entity
 */
class Branches
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
     * @ORM\Column(name="branch_id", type="integer", nullable=false)
     */
    private $branchId;

    /**
     * @var string
     *
     * @ORM\Column(name="branch_name", type="string", length=250, nullable=false)
     */
    private $branchName;



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
     * Set branchId
     *
     * @param integer $branchId
     * @return Branches
     */
    public function setBranchId($branchId)
    {
        $this->branchId = $branchId;

        return $this;
    }

    /**
     * Get branchId
     *
     * @return integer 
     */
    public function getBranchId()
    {
        return $this->branchId;
    }

    /**
     * Set branchName
     *
     * @param string $branchName
     * @return Branches
     */
    public function setBranchName($branchName)
    {
        $this->branchName = $branchName;

        return $this;
    }

    /**
     * Get branchName
     *
     * @return string 
     */
    public function getBranchName()
    {
        return $this->branchName;
    }
}
