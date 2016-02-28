<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserRebate
 *
 * @ORM\Table(name="user_rebate", indexes={@ORM\Index(name="user_rebate_ibfk_1_idx", columns={"user_id"}), @ORM\Index(name="user_rebate_ibfk_2_idx", columns={"rebate_status_id"}), @ORM\Index(name="user_rebate_ibfk_3_idx", columns={"rebate_month_id"})})
 * @ORM\Entity
 */
class UserRebate
{
    /**
     * @var integer
     *
     * @ORM\Column(name="user_rebate_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $userRebateId;

    /**
     * @var integer
     *
     * @ORM\Column(name="rank", type="integer", nullable=false)
     */
    private $rank;

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
     * @var \Application\Entity\RebateStatus
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\RebateStatus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="rebate_status_id", referencedColumnName="status_id")
     * })
     */
    private $rebateStatus;

    /**
     * @var \Application\Entity\RebateMonth
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\RebateMonth")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="rebate_month_id", referencedColumnName="rebate_month_id")
     * })
     */
    private $rebateMonth;



    /**
     * Get userRebateId
     *
     * @return integer 
     */
    public function getUserRebateId()
    {
        return $this->userRebateId;
    }

    /**
     * Set rank
     *
     * @param integer $rank
     * @return UserRebate
     */
    public function setRank($rank)
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * Get rank
     *
     * @return integer 
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * Set user
     *
     * @param \Application\Entity\Users $user
     * @return UserRebate
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
     * Set rebateStatus
     *
     * @param \Application\Entity\RebateStatus $rebateStatus
     * @return UserRebate
     */
    public function setRebateStatus(\Application\Entity\RebateStatus $rebateStatus = null)
    {
        $this->rebateStatus = $rebateStatus;

        return $this;
    }

    /**
     * Get rebateStatus
     *
     * @return \Application\Entity\RebateStatus 
     */
    public function getRebateStatus()
    {
        return $this->rebateStatus;
    }

    /**
     * Set rebateMonth
     *
     * @param \Application\Entity\RebateMonth $rebateMonth
     * @return UserRebate
     */
    public function setRebateMonth(\Application\Entity\RebateMonth $rebateMonth = null)
    {
        $this->rebateMonth = $rebateMonth;

        return $this;
    }

    /**
     * Get rebateMonth
     *
     * @return \Application\Entity\RebateMonth 
     */
    public function getRebateMonth()
    {
        return $this->rebateMonth;
    }
}
