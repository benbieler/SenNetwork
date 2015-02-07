<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\UserBundle\EventListener;

use Sententiaregum\Bundle\UserBundle\Event\AuthEvent;

/**
 * Authentication listener which checks if the user is locked or not
 */
class IsLockedListener
{
    /**
     * Handles the dispatched event
     *
     * @param \Sententiaregum\Bundle\UserBundle\Event\AuthEvent $event
     */
    public function onFinishedAuth(AuthEvent $event)
    {
        if ($event->getUser()->isLocked()) {
            $event->fail('This account is locked!');

            $event->stopPropagation();
        }
    }
}
