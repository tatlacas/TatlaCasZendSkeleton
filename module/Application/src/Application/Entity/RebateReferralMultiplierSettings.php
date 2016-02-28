<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RebateReferralMultiplierSettings
 *
 * @ORM\Table(name="rebate_referral_multiplier_settings")
 * @ORM\Entity
 */
class RebateReferralMultiplierSettings
{
    /**
     * @var integer
     *
     * @ORM\Column(name="rebate_referral_multiplier_settings_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $rebateReferralMultiplierSettingsId;

    /**
     * @var integer
     *
     * @ORM\Column(name="level", type="integer", nullable=false)
     */
    private $level;

    /**
     * @var integer
     *
     * @ORM\Column(name="rank", type="integer", nullable=false)
     */
    private $rank;

    /**
     * @var string
     *
     * @ORM\Column(name="rebate", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $rebate;



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
     * Set level
     *
     * @param integer $level
     * @return RebateReferralMultiplierSettings
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
     * Set rank
     *
     * @param integer $rank
     * @return RebateReferralMultiplierSettings
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
     * Set rebate
     *
     * @param string $rebate
     * @return RebateReferralMultiplierSettings
     */
    public function setRebate($rebate)
    {
        $this->rebate = $rebate;

        return $this;
    }

    /**
     * Get rebate
     *
     * @return string 
     */
    public function getRebate()
    {
        return $this->rebate;
    }
}
