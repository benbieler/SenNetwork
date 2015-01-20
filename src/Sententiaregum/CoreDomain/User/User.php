<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\CoreDomain\User;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Sententiaregum\CoreDomain\User\DTO\AuthDTO;
use Sententiaregum\CoreDomain\User\Event\AuthEvent;
use Sententiaregum\CoreDomain\User\Exception\UserDomainException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="SEN_User", indexes={@ORM\Index(name="sen_user_realname", columns={"realName"})})
 */
class User implements UserInterface
{
    const AUTHENTICATION_SUCCESSFUL_EVENT = 'sen.domain.user.auth.success';

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $username;

    /**
     * @var Password
     *
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $realName;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $registrationDate;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $lastAction;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $locked = false;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Sententiaregum\CoreDomain\User\Role", inversedBy="users")
     * @ORM\JoinTable(name="SEN_UserToRole",
     *     joinColumns={@ORM\JoinColumn(name="userId", referencedColumnName="userId")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="roleId", referencedColumnName="roleId")}
     * )
     */
    private $roles;

    /**
     * @var Token
     *
     * @ORM\OneToOne(targetEntity="Sententiaregum\CoreDomain\User\Token", inversedBy="user")
     * @ORM\JoinColumn(name="tokenId", referencedColumnName="tokenId")
     */
    private $token;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Sententiaregum\CoreDomain\User\User", mappedBy="following")
     */
    private $followers;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Sententiaregum\CoreDomain\User\User", inversedBy="followers")
     * @ORM\JoinTable(name="SEN_Followers",
     *      joinColumns={@ORM\JoinColumn(name="userId", referencedColumnName="userId")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="followerId", referencedColumnName="userId")}
     * )
     */
    private $following;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->roles     = new ArrayCollection();
        $this->followers = new ArrayCollection();
        $this->following = new ArrayCollection();
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
     * Set user id
     *
     * @param integer $userId
     *
     * @return $this
     */
    public function setUserId($userId)
    {
        $this->userId = (integer) $userId;

        return $this;
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
     * Set username
     *
     * @param string $username
     *
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = (string) $username;

        return $this;
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
     * Set password
     *
     * @param mixed $password
     *
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;
        $this->transformPassword();

        return $this;
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
     * Set email
     *
     * @param string $email
     *
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = (string) $email;

        return $this;
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
     * Set real name
     *
     * @param string $realName
     *
     * @return $this
     */
    public function setRealName($realName)
    {
        $this->realName = (string) $realName;

        return $this;
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
     * Set registration date
     *
     * @param DateTime $registrationDate
     *
     * @return $this
     */
    public function setRegistrationDate(DateTime $registrationDate)
    {
        $this->registrationDate = $registrationDate;

        return $this;
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
     * Set last action
     *
     * @param DateTime $lastAction
     *
     * @return $this
     */
    public function setLastAction(DateTime $lastAction)
    {
        $this->lastAction = $lastAction;

        return $this;
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
     * @return $this
     */
    public function createToken()
    {
        if (null === $this->token) {
            $apiKey = bin2hex(openssl_random_pseudo_bytes(100));
            $this->token = new Token($apiKey, $this);
        }

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
    public function addFollowing(User $user)
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
    public function removeFollowing(User $user)
    {
        $this->following->removeElement($user);

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
     * Authenticates the user by a dto
     *
     * @param AuthDTO $authCredentials
     * @param EventDispatcherInterface $dispatcher
     *
     * @return AuthEvent
     */
    public function authenticate(AuthDTO $authCredentials, EventDispatcherInterface $dispatcher)
    {
        $event = new AuthEvent($this);
        if (
            $this->username === $authCredentials->getUsername()
            && $this->getPassword()->compare($authCredentials->getPassword())
        ) {
            return $dispatcher->dispatch(static::AUTHENTICATION_SUCCESSFUL_EVENT, $event);
        }

        $event->setFailReason('Invalid credentials!');
        return $event;
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