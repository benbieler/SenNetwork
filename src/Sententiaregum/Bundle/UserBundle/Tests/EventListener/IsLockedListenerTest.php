<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\UserBundle\Tests\EventListener;

use Sententiaregum\Bundle\UserBundle\Event\AuthEvent;
use Sententiaregum\Bundle\UserBundle\EventListener\IsLockedListener;
use Sententiaregum\Domain\User\User;

class IsLockedListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Sententiaregum\Bundle\UserBundle\EventListener\IsLockedListener
     */
    private $listener;

    protected function setUp()
    {
        $this->listener = new IsLockedListener();
    }
    
    protected function tearDown()
    {
        $this->listener = null;
    }

    public function testUserIsLocked()
    {
        $user = new User('username', 'password', 'email@example.org');
        $user->lock();

        $event = new AuthEvent($user);
        $this->assertFalse($event->isFailed());

        $this->listener->onFinishedAuth($event);

        $this->assertTrue($event->isFailed());
        $this->assertTrue($event->isPropagationStopped());
        $this->assertSame('This account is locked!', $event->getFailReason());
    }
}
