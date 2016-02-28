<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClustersPayments
 *
 * @ORM\Table(name="clusters_payments", uniqueConstraints={@ORM\UniqueConstraint(name="reference_id", columns={"reference_id"})}, indexes={@ORM\Index(name="user_to_pay", columns={"user_to_pay"})})
 * @ORM\Entity
 */
class ClustersPayments
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
     * @ORM\Column(name="linked_ids", type="string", length=100, nullable=false)
     */
    private $linkedIds;

    /**
     * @var integer
     *
     * @ORM\Column(name="transanction_status", type="integer", nullable=false)
     */
    private $transanctionStatus = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="transanction_type", type="integer", nullable=false)
     */
    private $transanctionType;

    /**
     * @var string
     *
     * @ORM\Column(name="reference_id", type="string", length=50, nullable=false)
     */
    private $referenceId;

    /**
     * @var \Application\Entity\Users
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_to_pay", referencedColumnName="user_id")
     * })
     */
    private $userToPay;



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
     * Set linkedIds
     *
     * @param string $linkedIds
     * @return ClustersPayments
     */
    public function setLinkedIds($linkedIds)
    {
        $this->linkedIds = $linkedIds;

        return $this;
    }

    /**
     * Get linkedIds
     *
     * @return string 
     */
    public function getLinkedIds()
    {
        return $this->linkedIds;
    }

    /**
     * Set transanctionStatus
     *
     * @param integer $transanctionStatus
     * @return ClustersPayments
     */
    public function setTransanctionStatus($transanctionStatus)
    {
        $this->transanctionStatus = $transanctionStatus;

        return $this;
    }

    /**
     * Get transanctionStatus
     *
     * @return integer 
     */
    public function getTransanctionStatus()
    {
        return $this->transanctionStatus;
    }

    /**
     * Set transanctionType
     *
     * @param integer $transanctionType
     * @return ClustersPayments
     */
    public function setTransanctionType($transanctionType)
    {
        $this->transanctionType = $transanctionType;

        return $this;
    }

    /**
     * Get transanctionType
     *
     * @return integer 
     */
    public function getTransanctionType()
    {
        return $this->transanctionType;
    }

    /**
     * Set referenceId
     *
     * @param string $referenceId
     * @return ClustersPayments
     */
    public function setReferenceId($referenceId)
    {
        $this->referenceId = $referenceId;

        return $this;
    }

    /**
     * Get referenceId
     *
     * @return string 
     */
    public function getReferenceId()
    {
        return $this->referenceId;
    }

    /**
     * Set userToPay
     *
     * @param \Application\Entity\Users $userToPay
     * @return ClustersPayments
     */
    public function setUserToPay(\Application\Entity\Users $userToPay = null)
    {
        $this->userToPay = $userToPay;

        return $this;
    }

    /**
     * Get userToPay
     *
     * @return \Application\Entity\Users 
     */
    public function getUserToPay()
    {
        return $this->userToPay;
    }
}
