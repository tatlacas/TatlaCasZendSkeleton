<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdminUpdates
 *
 * @ORM\Table(name="admin_updates", indexes={@ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity
 */
class AdminUpdates
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
     * @var boolean
     *
     * @ORM\Column(name="send_state", type="boolean", nullable=false)
     */
    private $sendState;

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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set sendState
     *
     * @param boolean $sendState
     * @return AdminUpdates
     */
    public function setSendState($sendState)
    {
        $this->sendState = $sendState;

        return $this;
    }

    /**
     * Get sendState
     *
     * @return boolean 
     */
    public function getSendState()
    {
        return $this->sendState;
    }

    /**
     * Set user
     *
     * @param \Application\Entity\Users $user
     * @return AdminUpdates
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
}
