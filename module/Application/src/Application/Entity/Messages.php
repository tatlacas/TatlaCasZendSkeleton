<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Messages
 *
 * @ORM\Table(name="messages")
 * @ORM\Entity
 */
class Messages
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
     * @ORM\Column(name="message", type="string", length=200, nullable=false)
     */
    private $message;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=200, nullable=false)
     */
    private $title;

    /**
     * @var boolean
     *
     * @ORM\Column(name="state", type="boolean", nullable=false)
     */
    private $state;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_time", type="datetime", nullable=true)
     */
    private $dateTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="extra_one", type="integer", nullable=true)
     */
    private $extraOne;

    /**
     * @var string
     *
     * @ORM\Column(name="extra_two", type="string", length=100, nullable=true)
     */
    private $extraTwo;



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
     * Set message
     *
     * @param string $message
     * @return Messages
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Messages
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set state
     *
     * @param boolean $state
     * @return Messages
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return boolean 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set dateTime
     *
     * @param \DateTime $dateTime
     * @return Messages
     */
    public function setDateTime($dateTime)
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    /**
     * Get dateTime
     *
     * @return \DateTime 
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * Set extraOne
     *
     * @param integer $extraOne
     * @return Messages
     */
    public function setExtraOne($extraOne)
    {
        $this->extraOne = $extraOne;

        return $this;
    }

    /**
     * Get extraOne
     *
     * @return integer 
     */
    public function getExtraOne()
    {
        return $this->extraOne;
    }

    /**
     * Set extraTwo
     *
     * @param string $extraTwo
     * @return Messages
     */
    public function setExtraTwo($extraTwo)
    {
        $this->extraTwo = $extraTwo;

        return $this;
    }

    /**
     * Get extraTwo
     *
     * @return string 
     */
    public function getExtraTwo()
    {
        return $this->extraTwo;
    }
}
