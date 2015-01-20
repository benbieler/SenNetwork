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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Sententiaregum\CoreDomain\User\Exception\UserDomainException;
use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * Entity which represents a role
 *
 * @ORM\Table(name="SEN_Role", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="sen_role_role", columns={"name"})
 * })
 * @ORM\Entity()
 */
class Role implements RoleInterface
{
    /**
     * @var string
     */
    const USER  = 'ROLE_USER';

    /**
     * @var string
     */
    const ADMIN = 'ROLE_ADMIN';

    /**
     * @var integer
     *
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $roleId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Sententiaregum\CoreDomain\User\User", mappedBy="roles")
     */
    private $users;

    /**
     * @param string $role
     *
     * @throws UserDomainException If the role is invalid
     */
    public function __construct($role)
    {
        $roles = [static::USER, static::ADMIN];
        if (!in_array($role, $roles)) {
            throw new UserDomainException(sprintf('Invalid role entered: allowed roles are %s', implode($roles, ', ')));
        }

        $this->name  = $role;
        $this->users = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function getRole()
    {
        return $this->name;
    }

    /**
     * Return user id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->roleId;
    }

    /**
     * Return users
     *
     * @return User[]
     */
    public function getUsers()
    {
        return $this->users->toArray();
    }

    /**
     * Adds a user
     *
     * @param User $user
     *
     * @return $this
     */
    public function addUser(User $user)
    {
        $this->users->add($user);

        return $this;
    }

    /**
     * Removes a user
     *
     * @param User $user
     *
     * @return $this
     */
    public function removeUser(User $user)
    {
        $this->users->removeElement($user);

        return $this;
    }
}
