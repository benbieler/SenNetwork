<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bridge\User\Event;

use Sententiaregum\CoreDomain\User\User;
use Symfony\Component\EventDispatcher\Event;

/**
 * Event which will be dispatched after a successful user creation
 */
class UserCreatedEvent extends Event
{
    /**
     * @var User
     */
    private $user;

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Returns the user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}
