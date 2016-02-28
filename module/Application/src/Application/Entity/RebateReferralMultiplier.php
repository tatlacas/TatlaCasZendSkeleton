<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RebateReferralMultiplier
 *
 * @ORM\Table(name="rebate_referral_multiplier", indexes={@ORM\Index(name="rebate_referral_multiplier_ibfk_1_idx", columns={"rebate_month_id"}), @ORM\Index(name="rebate_referral_multiplier_2_idx", columns={"user_id"}), @ORM\Index(name="rebate_referral_multiplier_ibfk_1_idx1", columns={"rebate_referral_multiplier_settings_id"})})
 * @ORM\Entity
 */
class RebateReferralMultiplier
{
    /**
     * @var integer
     *
     * @ORM\Column(name="rebate_referral_multiplier_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $rebateReferralMultiplierId;

    /**
     * @var integer
     *
     * @ORM\Column(name="referrer", type="integer", nullable=false)
     */
    private $referrer;

    /**
     * @var integer
     *
     * @ORM\Column(name="rank", type="integer", nullable=false)
     */
    private $rank;

    /**
     * @var integer
     *
     * @ORM\Column(name="level", type="integer", nullable=true)
     */
    private $level;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=true)
     */
    private $userId;

    /**
     * @var integer
     *
     * @ORM\Column(name="row_num", type="integer", nullable=false)
     */
    private $rowNum;

    /**
     * @var integer
     *
     * @ORM\Column(name="rebate_referral_multiplier_settings_id", type="integer", nullable=true)
     */
    private $rebateReferralMultiplierSettingsId;

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
     * Get rebateReferralMultiplierId
     *
     * @return integer 
     */
    public function getRebateReferralMultiplierId()
    {
        return $this->rebateReferralMultiplierId;
    }

    /**
     * Set referrer
     *
     * @param integer $referrer
     * @return RebateReferralMultiplier
     */
    public function setReferrer($referrer)
    {
        $this->referrer = $referrer;

        return $this;
    }

    /**
     * Get referrer
     *
     * @return integer 
     */
    public function getReferrer()
    {
        return $this->referrer;
    }

    /**
     * Set rank
     *
     * @param integer $rank
     * @return RebateReferralMultiplier
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
     * Set level
     *
     * @param integer $level
     * @return RebateReferralMultiplier
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return integer 
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     * @return RebateReferralMultiplier
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
     * Set rowNum
     *
     * @param integer $rowNum
     * @return RebateReferralMultiplier
     */
    public function setRowNum($rowNum)
    {
        $this->rowNum = $rowNum;

        return $this;
    }

    /**
     * Get rowNum
     *
     * @return integer 
     */
    public function getRowNum()
    {
        return $this->rowNum;
    }

    /**
     * Set rebateReferralMultiplierSettingsId
     *
     * @param integer $rebateReferralMultiplierSettingsId
     * @return RebateReferralMultiplier
     */
    public function setRebateReferralMultiplierSettingsId($rebateReferralMultiplierSettingsId)
    {
        $this->rebateReferralMultiplierSettingsId = $rebateReferralMultiplierSettingsId;

        return $this;
    }

    /**
     * Get rebateReferralMultiplierSettingsId
     *
     * @return integer 
     */
    public function getRebateReferralMultiplierSettingsId()
    {
        return $this->rebateReferralMultiplierSettingsId;
    }

    /**
     * Set rebateMonth
     *
     * @param \Application\Entity\RebateMonth $rebateMonth
     * @return RebateReferralMultiplier
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
