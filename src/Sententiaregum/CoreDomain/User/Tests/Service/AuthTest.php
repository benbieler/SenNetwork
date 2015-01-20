<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\CoreDomain\User\Tests\Service;

use Sententiaregum\CoreDomain\User\DTO\AuthDTO;
use Sententiaregum\CoreDomain\User\Event\AuthEvent;
use Sententiaregum\CoreDomain\User\Service\Auth;
use Sententiaregum\CoreDomain\User\User;
use Sententiaregum\CoreDomain\User\UserAggregateRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class AuthTest extends \PHPUnit_Framework_TestCase
{
    public function testInvalidUserName()
    {
        $repository = $this->getMock(UserAggregateRepository::class);
        $repository
            ->expects($this->any())
            ->method('findOneByName')
            ->will($this->returnValue(null));

        $credentials = new AuthDTO('invalid', '123456');
        $userAuth    = new Auth($repository, $this->getMock(EventDispatcherInterface::class));

        /** @var AuthEvent $result */
        $result = $userAuth->authenticateUser($credentials);
        $this->assertInstanceOf(AuthEvent::class, $result);

        $this->assertNull($result->getUser());
        $this->assertSame('Invalid credentials!', $result->getFailReason());
    }

    public function testSuccessfulAuthentication()
    {
        $repository = $this->getMock(UserAggregateRepository::class);
        $repository
            ->expects($this->any())
            ->method('findOneByName')
            ->will($this->returnValue(new User()));

        $dispatcher = $this->getMock(EventDispatcherInterface::class);
        $dispatcher
            ->expects($this->any())
            ->method('dispatch')
            ->will($this->returnArgument(1));

        $credentials = new AuthDTO('admin', '123456');
        $userAuth    = new Auth($repository, $dispatcher);

        /** @var AuthEvent $result */
        $result = $userAuth->authenticateUser($credentials);
        $this->assertInstanceOf(AuthEvent::class, $result);

        $this->assertInstanceOf(User::class, $result->getUser());
        $this->assertNull($result->getFailReason());
    }
}
