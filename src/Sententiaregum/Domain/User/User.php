<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Domain\User;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Sententiaregum\Domain\User\Exception\UserDomainException;
use Sententiaregum\Domain\User\Service\ApiKeyGeneratorInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User entity
 */
class User implements UserInterface
{
    /**
     * @var integer
     */
    private $userId;

    /**
     * @var string
     */
    private $username;

    /**
     * @var Password
     */
    private $password;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $realName;

    /**
     * @var DateTime
     */
    private $registrationDate;

    /**
     * @var DateTime
     */
    private $lastAction;

    /**
     * @var boolean
     */
    private $locked = false;

    /**
     * @var ArrayCollection
     */
    private $roles;

    /**
     * @var Token
     */
    private $token;

    /**
     * @var ArrayCollection
     */
    private $followers;

    /**
     * @var ArrayCollection
     */
    private $following;

    /**
     * @param string $username
     * @param string|Password $password
     * @param string $email
     * @param string $realName
     * @param DateTime $registrationDate
     * @param DateTime $lastAction
     *
     * @throws UserDomainException If email is invalid
     */
    public function __construct(
        $username, $password, $email, $realName = null, DateTime $registrationDate = null,  DateTime $lastAction = null
    ) {
        $this->username         = (string) $username;
        $this->password         = $password;
        $this->email            = (string) $email;
        $this->realName         = (string) $realName;
        $this->registrationDate = $registrationDate ?: new DateTime();
        $this->lastAction       = $lastAction ?: new DateTime();

        $this->roles     = new ArrayCollection();
        $this->followers = new ArrayCollection();
        $this->following = new ArrayCollection();

        $this->transformPassword();

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new UserDomainException('Email is invalid!');
        }
    }

    /**
     * Return user id
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Return username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Return password
     *
     * @return Password
     */
    public function getPassword()
    {
        $this->transformPassword();
        return $this->password;
    }

    /**
     * Creates a value object by using the given password
     *
     * @throws UserDomainException If the password property to transform is empty
     * @throws UserDomainException If the password is not a string and not a Password instance
     */
    private function transformPassword()
    {
        if (null === $this->password) {
            throw new UserDomainException('Given password property is empty!');
        }

        if (is_string($this->password)) {
            $this->password = new Password($this->password, true);
        } else if ($this->password instanceof Password) {
            $this->password->hash();
        } else {
            throw new UserDomainException('Invalid data type (string and Password are allowed only)!');
        }
    }

    /**
     * Return email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Return real name
     *
     * @return string
     */
    public function getRealName()
    {
        return $this->realName;
    }

    /**
     * Return registration date
     *
     * @return DateTime
     */
    public function getRegistrationDate()
    {
        return $this->registrationDate;
    }

    /**
     * Return last action
     *
     * @return DateTime
     */
    public function getLastAction()
    {
        return $this->lastAction;
    }

    /**
     * Checks if the user is locked
     *
     * @return boolean
     */
    public function isLocked()
    {
        return $this->locked;
    }

    /**
     * Locks the user
     *
     * @return $this
     */
    public function lock()
    {
        $this->locked = true;

        return $this;
    }

    /**
     * Unlocks the user
     *
     * @return $this
     */
    public function unlock()
    {
        $this->locked = false;

        return $this;
    }

    /**
     * Returns a list of roles
     *
     * @return Role[]
     */
    public function getRoles()
    {
        return $this->roles->toArray();
    }

    /**
     * Add role
     *
     * @param Role $role
     *
     * @return $this
     */
    public function addRole(Role $role)
    {
        $this->roles->add($role);

        return $this;
    }

    /**
     * Remove role
     *
     * @param Role $role
     *
     * @return $this
     */
    public function removeRole(Role $role)
    {
        $this->roles->removeElement($role);

        return $this;
    }

    /**
     * Returns the token
     *
     * @return Token
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Creates a token
     *
     * @param ApiKeyGeneratorInterface $generator
     *
     * @return $this
     */
    public function createToken(ApiKeyGeneratorInterface $generator)
    {
        if (null === $this->token) {
            $this->token = new Token($generator->generate(), $this);
        }

        return $this;
    }

    /**
     * Removes the token
     *
     * @return $this
     */
    public function removeToken()
    {
        $this->token = null;

        return $this;
    }

    /**
     * Returns the followers
     *
     * @return User[]
     */
    public function getFollowers()
    {
        return $this->followers->toArray();
    }

    /**
     * Removes a follower
     *
     * @param User $user
     *
     * @return $this
     */
    public function removeFollower(User $user)
    {
        $this->followers->removeElement($user);

        return $this;
    }

    /**
     * Add follower
     *
     * @param User $user
     *
     * @return $this
     */
    public function addFollower(User $user)
    {
        $this->followers->add($user);

        return $this;
    }

    /**
     * Return following users
     *
     * @return User[]
     */
    public function getFollowing()
    {
        return $this->following->toArray();
    }

    /**
     * Add following user
     *
     * @param User $user
     *
     * @return $this
     */
    public function follow(User $user)
    {
        $this->following->add($user);

        return $this;
    }

    /**
     * Remove following user
     *
     * @param User $user
     *
     * @return $this
     */
    public function unfollow(User $user)
    {
        $this->following->removeElement($user);

        return $this;
    }

    /**
     * Updates the last action
     *
     * @param DateTime $lastActionDate
     *
     * @return $this
     */
    public function updateLastAction(DateTime $lastActionDate)
    {
        $this->lastAction = $lastActionDate;

        return $this;
    }

    /**
     * Remove all roles
     *
     * @return $this
     */
    public function removeRoles()
    {
        $this->roles->clear();

        return $this;
    }

    /**
     * Removes all users
     *
     * @return $this
     */
    public function removeFollowers()
    {
        $this->followers->clear();

        return $this;
    }

    /**
     * Unfollows many users
     *
     * @return $this
     */
    public function unfollowMany()
    {
        $this->following->clear();

        return $this;
    }

    /**
     * Checks if the user is online
     *
     * @return boolean
     */
    public function isOnline()
    {
        return time() - $this->getLastAction()->getTimestamp() <= 300;
    }

    /**
     * Returns null because the salt will be generated dynamically using the password api
     */
    public function getSalt()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
    }
}
