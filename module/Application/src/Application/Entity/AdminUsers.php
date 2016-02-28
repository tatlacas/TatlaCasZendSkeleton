<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdminUsers
 *
 * @ORM\Table(name="admin_users")
 * @ORM\Entity
 */
class AdminUsers
{
    /**
     * @var integer
     *
     * @ORM\Column(name="admin_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $adminId;

    /**
     * @var string
     *
     * @ORM\Column(name="user_name", type="string", length=64, nullable=false)
     */
    private $userName;

    /**
     * @var string
     *
     * @ORM\Column(name="user_password_hash", type="string", length=255, nullable=false)
     */
    private $userPasswordHash;

    /**
     * @var string
     *
     * @ORM\Column(name="user_email", type="string", length=64, nullable=false)
     */
    private $userEmail;



    /**
     * Get adminId
     *
     * @return integer 
     */
    public function getAdminId()
    {
        return $this->adminId;
    }

    /**
     * Set userName
     *
     * @param string $userName
     * @return AdminUsers
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;

        return $this;
    }

    /**
     * Get userName
     *
     * @return string 
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * Set userPasswordHash
     *
     * @param string $userPasswordHash
     * @return AdminUsers
     */
    public function setUserPasswordHash($userPasswordHash)
    {
        $this->userPasswordHash = $userPasswordHash;

        return $this;
    }

    /**
     * Get userPasswordHash
     *
     * @return string 
     */
    public function getUserPasswordHash()
    {
        return $this->userPasswordHash;
    }

    /**
     * Set userEmail
     *
     * @param string $userEmail
     * @return AdminUsers
     */
    public function setUserEmail($userEmail)
    {
        $this->userEmail = $userEmail;

        return $this;
    }

    /**
     * Get userEmail
     *
     * @return string 
     */
    public function getUserEmail()
    {
        return $this->userEmail;
    }
}
