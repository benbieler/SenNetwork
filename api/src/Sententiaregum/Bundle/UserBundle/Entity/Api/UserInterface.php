<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\UserBundle\Entity\Api;

use DateTime;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

interface UserInterface extends AdvancedUserInterface
{
    /**
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * @return integer
     */
    public function getId();

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email);

    /**
     * @return string
     */
    public function getEmail();

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword($password);

    /**
     * @return string
     */
    public function getPassword();

    /**
     * @param string $apiToken
     * @return $this
     */
    public function setApiToken($apiToken = null);

    /**
     * @return string
     */
    public function getApiToken();

    /**
     * @param \DateTime $registrationDate
     * @return $this
     */
    public function setRegistrationDate(DateTime $registrationDate);

    /**
     * @return \DateTime
     */
    public function getRegistrationDate();

    /**
     * @param string $realName
     * @return $this
     */
    public function setRealName($realName);

    /**
     * @return string
     */
    public function getRealName();

    /**
     * @param \DateTime $lastAction
     * @return $this
     */
    public function setLastAction(DateTime $lastAction);

    /**
     * @return \DateTime
     */
    public function getLastAction();

    /**
     * @return boolean
     */
    public function isOnline();

    /**
     * @param boolean $locked
     * @return $this
     */
    public function setLocked($locked);

    /**
     * @param string $salt
     * @return $this
     */
    public function setSalt($salt = null);

    /**
     * @param \Symfony\Component\Security\Core\Role\Role[] $roles
     * @return $this
     */
    public function setRoles(array $roles);
}
