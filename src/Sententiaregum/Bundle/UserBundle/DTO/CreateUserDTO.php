<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\UserBundle\DTO;

use Sententiaregum\Bundle\UserBundle\Validator\Constraint\UniqueUser;
use Sententiaregum\Domain\User\Role;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * DTO containing the input of a user creation
 */
class CreateUserDTO
{
    /**
     * @var string
     *
     * @Assert\NotBlank(message="user.trans.username.not_blank")
     * @Assert\Length(
     *     min="3",
     *     max="32",
     *     minMessage="user.trans.username.short",
     *     maxMessage="user.trans.username.long"
     * )
     * @Assert\Regex(pattern="/^[A-zäöüÄÖÜß0-9_\-\.]+$/i", message="user.trans.username.regex")
     * @UniqueUser(message="user.trans.username.in_use", property="username")
     */
    private $username;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="user.trans.password.not_blank")
     * @Assert\Length(
     *     min="6",
     *     max="4096",
     *     minMessage="user.trans.password.short",
     *     maxMessage="user.trans.password.long"
     * )
     */
    private $password;

    /**
     * @var string
     *
     * @Assert\Email(message="user.trans.email.invalid")
     * @Assert\NotBlank(message="user.trans.email.empty")
     * @UniqueUser(message="user.trans.email.in_use", property="email")
     */
    private $email;

    /**
     * @var Role[]
     */
    private $roles;

    /**
     * @var string
     *
     * @Assert\Length(
     *     min="3",
     *     max="128",
     *     minMessage="user.trans.realname.short",
     *     maxMessage="user.trans.realname.long"
     * )
     * @Assert\Regex(pattern="/^[A-zäöüÄÖÜß0-9 ]+$/i", message="user.trans.realname.regex")
     */
    private $realName;

    /**
     * @param string $username
     * @param string $password
     * @param string $email
     * @param Role[] $roles
     * @param string $realName
     */
    public function __construct($username, $password, $email, array $roles, $realName = null)
    {
        $this->username = (string) $username;
        $this->password = (string) $password;
        $this->email    = (string) $email;
        $this->realname = (string) $realName;

        array_walk(
            $roles,
            function (&$role) {
                if (!$role instanceof Role) {
                    $role = new Role($role);
                }
            }
        );
        $this->roles = $roles;
    }

    /**
     * Returns the name
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Sets the name
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
     * Returns the password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Sets the password
     *
     * @param string $password
     *
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = (string) $password;

        return $this;
    }

    /**
     * Returns the email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets the email
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
     * Returns the roles of the new user
     *
     * @return \Sententiaregum\Domain\User\Role[]
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Returns the real name
     *
     * @return string
     */
    public function getRealName()
    {
        return $this->realName;
    }

    /**
     * Sets the real name
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
}
