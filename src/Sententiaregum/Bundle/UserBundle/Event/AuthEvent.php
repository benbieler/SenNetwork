<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\UserBundle\Event;

use Sententiaregum\Domain\User\User;
use Symfony\Component\EventDispatcher\Event;

/**
 * Event containing all the authentication information
 */
class AuthEvent extends Event
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var string
     */
    private $failReason;

    /**
     * @param \Sententiaregum\Domain\User\User $user
     */
    public function __construct(User $user = null)
    {
        $this->user = $user;
    }

    /**
     * Return user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Return fail reason
     *
     * @return string
     */
    public function getFailReason()
    {
        return $this->failReason;
    }

    /**
     * Set fail reason
     *
     * @param string $failReason
     *
     * @return $this
     */
    public function fail($failReason)
    {
        $this->failReason = (string) $failReason;
        return $this;
    }

    /**
     * Checks if the event is failed
     *
     * @return boolean
     */
    public function isFailed()
    {
        return $this->getFailReason() !== null;
    }
}
