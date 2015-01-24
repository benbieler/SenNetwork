<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bridge\User\Tests\Security;

use Sententiaregum\Bridge\User\Security\UserProvider;
use Sententiaregum\CoreDomain\User\User;
use Sententiaregum\CoreDomain\User\UserAggregateRepositoryInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserProviderTest extends \PHPUnit_Framework_TestCase 
{
    public function testLoadByUsername()
    {
        $userMock = $this->getMock(User::class);
        $userMock
            ->expects($this->any())
            ->method('getUsername')
            ->will($this->returnValue('foobar'));

        $repository = $this->getMock(UserAggregateRepositoryInterface::class);
        $repository
            ->expects($this->any())
            ->method('findOneByName')
            ->will($this->returnValue($userMock));

        $userProvider = new UserProvider($repository);

        $this->assertSame($userMock, $userProvider->loadUserByUsername('foobar'));
    }

    public function testUserSupport()
    {
        $userProvider = new UserProvider($this->getMock(UserAggregateRepositoryInterface::class));

        $this->assertTrue($userProvider->supportsClass(User::class));
        $this->assertFalse($userProvider->supportsClass(UserInterface::class));
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\UnsupportedUserException
     */
    public function testThrowUnsupportedExceptionForInvalidUser()
    {
        $mock = $this->getMock(UserInterface::class);

        $userProvider = new UserProvider($this->getMock(UserAggregateRepositoryInterface::class));
        $userProvider->refreshUser($mock);
    }

    public function testRefreshUser()
    {
        $stub = new User();
        $repo = $this->getMock(UserAggregateRepositoryInterface::class);
        $repo
            ->expects($this->any())
            ->method('findOneByName')
            ->will($this->returnValue(clone $stub));

        $userProvider = new UserProvider($repo);

        $this->assertInstanceOf(User::class, $result = $userProvider->refreshUser($stub));
        $this->assertNotSame($result, $stub);
    }

    /**
     * @dataProvider getApiKeys
     */
    public function testFindByApiKey($key)
    {
        $stub = new User();
        $repo = $this->getMock(UserAggregateRepositoryInterface::class);
        $repo
            ->expects($this->any())
            ->method('findOneByApiKey')
            ->will($this->returnValue($stub));

        $this->assertSame($stub, (new UserProvider($repo))->findUserByApiKey($key));
    }

    /**
     * Provides a set of api keys
     */
    public function getApiKeys()
    {
        return [
            [
                uniqid()
            ]
        ];
    }
}
