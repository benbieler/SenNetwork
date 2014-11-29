<?php

namespace Sententiaregum\Bundle\UserBundle\Entity;

use DateTime;
use Sententiaregum\Bundle\UserBundle\Entity\Api\UserInterface;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\EquatableInterface;

class User implements UserInterface, EquatableInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $realName;

    /**
     * @var string
     */
    private $email;

    /**
     * @var \DateTime
     */
    private $lastAction;

    /**
     * @var \DateTime
     */
    private $registrationDate;

    /**
     * @var string
     */
    private $salt;

    /**
     * @var string
     */
    private $apiToken;

    /**
     * @var boolean
     */
    private $locked;

    /**
     * @var Role[]
     */
    private $roles = [];

    /**
     * @param string $apiToken
     * @return $this
     */
    public function setApiToken($apiToken = null)
    {
        $this->apiToken = $apiToken;
        return $this;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = (string) $email;
        return $this;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = (integer) $id;
        return $this;
    }

    /**
     * @param \DateTime $lastAction
     * @return $this
     */
    public function setLastAction(DateTime $lastAction)
    {
        $this->lastAction = $lastAction;
        return $this;
    }

    /**
     * @param boolean $locked
     * @return $this
     */
    public function setLocked($locked)
    {
        $this->locked = (boolean) $locked;
        return $this;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = (string) $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getRealName()
    {
        return $this->realName;
    }

    /**
     * @param string $realName
     * @return $this
     */
    public function setRealName($realName)
    {
        $this->realName = (string) $realName;
        return $this;
    }

    /**
     * @param \DateTime $registrationDate
     * @return $this
     */
    public function setRegistrationDate(DateTime $registrationDate)
    {
        $this->registrationDate = $registrationDate;
        return $this;
    }

    /**
     * @param string $salt
     * @return $this
     */
    public function setSalt($salt = null)
    {
        $this->salt = (string) $salt;
        return $this;
    }

    /**
     * @param string $username
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = (string) $username;
        return $this;
    }

    /**
     * @param Role[] $roles
     * @return $this
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;
        return $this;
    }


    /**
     * @return boolean
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * @return boolean
     */
    public function isAccountNonLocked()
    {
        return !$this->locked;
    }

    /**
     * @return boolean
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * @return boolean
     */
    public function isEnabled()
    {
        return true;
    }

    public function eraseCredentials()
    {
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getApiToken()
    {
        return $this->apiToken;
    }

    /**
     * @return \DateTime
     */
    public function getRegistrationDate()
    {
        return $this->registrationDate;
    }

    /**
     * @return \DateTime
     */
    public function getLastAction()
    {
        return $this->lastAction;
    }

    /**
     * @return \Symfony\Component\Security\Core\Role\Role[]
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @return null|string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return boolean
     */
    public function isOnline()
    {
        return $this->lastAction->getTimestamp() >= 180;
    }

    /**
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     * @return boolean
     */
    public function isEqualTo(\Symfony\Component\Security\Core\User\UserInterface $user)
    {
        if (!$user instanceof static) {
            return false;
        }

        return $user->getUsername() === $this->getUsername()
            && $user->getId() === $this->getId()
            && $user->getPassword() === $this->getPassword()
            && $user->getSalt() === $this->getSalt();
    }
}
