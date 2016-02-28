<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Users
 *
 * @ORM\Table(name="users", indexes={@ORM\Index(name="branch_id", columns={"branch_id"}), @ORM\Index(name="users_ibfk_1_idx", columns={"referer"})})
 * @ORM\Entity
 */
class Users
{
    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $userId;

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
     * @ORM\Column(name="pincode", type="string", length=400, nullable=false)
     */
    private $pincode;

    /**
     * @var string
     *
     * @ORM\Column(name="gcm_regid", type="text", nullable=false)
     */
    private $gcmRegid;

    /**
     * @var string
     *
     * @ORM\Column(name="phone_number", type="string", length=20, nullable=false)
     */
    private $phoneNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="id_number", type="string", length=20, nullable=false)
     */
    private $idNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="date_of_birth", type="string", length=20, nullable=false)
     */
    private $dateOfBirth;

    /**
     * @var string
     *
     * @ORM\Column(name="created_at", type="string", length=20, nullable=false)
     */
    private $createdAt;

    /**
     * @var \Application\Entity\Users
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="referer", referencedColumnName="user_id")
     * })
     */
    private $referer;

    /**
     * @var \Application\Entity\Branches
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Branches")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="branch_id", referencedColumnName="id")
     * })
     */
    private $branch;



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
     * Set firstName
     *
     * @param string $firstName
     * @return Users
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
     * @return Users
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
     * @return Users
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
     * Set pincode
     *
     * @param string $pincode
     * @return Users
     */
    public function setPincode($pincode)
    {
        $this->pincode = $pincode;

        return $this;
    }

    /**
     * Get pincode
     *
     * @return string 
     */
    public function getPincode()
    {
        return $this->pincode;
    }

    /**
     * Set gcmRegid
     *
     * @param string $gcmRegid
     * @return Users
     */
    public function setGcmRegid($gcmRegid)
    {
        $this->gcmRegid = $gcmRegid;

        return $this;
    }

    /**
     * Get gcmRegid
     *
     * @return string 
     */
    public function getGcmRegid()
    {
        return $this->gcmRegid;
    }

    /**
     * Set phoneNumber
     *
     * @param string $phoneNumber
     * @return Users
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return string 
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set idNumber
     *
     * @param string $idNumber
     * @return Users
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
     * @param string $dateOfBirth
     * @return Users
     */
    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    /**
     * Get dateOfBirth
     *
     * @return string 
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * Set createdAt
     *
     * @param string $createdAt
     * @return Users
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return string 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set referer
     *
     * @param \Application\Entity\Users $referer
     * @return Users
     */
    public function setReferer(\Application\Entity\Users $referer = null)
    {
        $this->referer = $referer;

        return $this;
    }

    /**
     * Get referer
     *
     * @return \Application\Entity\Users 
     */
    public function getReferer()
    {
        return $this->referer;
    }

    /**
     * Set branch
     *
     * @param \Application\Entity\Branches $branch
     * @return Users
     */
    public function setBranch(\Application\Entity\Branches $branch = null)
    {
        $this->branch = $branch;

        return $this;
    }

    /**
     * Get branch
     *
     * @return \Application\Entity\Branches 
     */
    public function getBranch()
    {
        return $this->branch;
    }
}
