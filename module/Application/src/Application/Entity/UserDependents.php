<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserDependents
 *
 * @ORM\Table(name="user_dependents", indexes={@ORM\Index(name="user_id", columns={"user_id"}), @ORM\Index(name="relation_type", columns={"relation_type"})})
 * @ORM\Entity
 */
class UserDependents
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
     * @ORM\Column(name="first_name", type="string", length=60, nullable=false)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=60, nullable=false)
     */
    private $lastName;

    /**
     * @var boolean
     *
     * @ORM\Column(name="gender", type="boolean", nullable=false)
     */
    private $gender;

    /**
     * @var string
     *
     * @ORM\Column(name="id_number", type="string", length=20, nullable=true)
     */
    private $idNumber;

    /**
     * @var integer
     *
     * @ORM\Column(name="date_of_birth", type="bigint", nullable=false)
     */
    private $dateOfBirth;

    /**
     * @var integer
     *
     * @ORM\Column(name="joined_at", type="bigint", nullable=false)
     */
    private $joinedAt;

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
     * @var \Application\Entity\UserRelationshipTypes
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\UserRelationshipTypes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="relation_type", referencedColumnName="id")
     * })
     */
    private $relationType;



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
     * Set firstName
     *
     * @param string $firstName
     * @return UserDependents
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return UserDependents
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set gender
     *
     * @param boolean $gender
     * @return UserDependents
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return boolean 
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set idNumber
     *
     * @param string $idNumber
     * @return UserDependents
     */
    public function setIdNumber($idNumber)
    {
        $this->idNumber = $idNumber;

        return $this;
    }

    /**
     * Get idNumber
     *
     * @return string 
     */
    public function getIdNumber()
    {
        return $this->idNumber;
    }

    /**
     * Set dateOfBirth
     *
     * @param integer $dateOfBirth
     * @return UserDependents
     */
    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    /**
     * Get dateOfBirth
     *
     * @return integer 
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * Set joinedAt
     *
     * @param integer $joinedAt
     * @return UserDependents
     */
    public function setJoinedAt($joinedAt)
    {
        $this->joinedAt = $joinedAt;

        return $this;
    }

    /**
     * Get joinedAt
     *
     * @return integer 
     */
    public function getJoinedAt()
    {
        return $this->joinedAt;
    }

    /**
     * Set user
     *
     * @param \Application\Entity\Users $user
     * @return UserDependents
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
     * Set relationType
     *
     * @param \Application\Entity\UserRelationshipTypes $relationType
     * @return UserDependents
     */
    public function setRelationType(\Application\Entity\UserRelationshipTypes $relationType = null)
    {
        $this->relationType = $relationType;

        return $this;
    }

    /**
     * Get relationType
     *
     * @return \Application\Entity\UserRelationshipTypes 
     */
    public function getRelationType()
    {
        return $this->relationType;
    }
}
