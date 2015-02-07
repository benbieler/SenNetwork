<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\UserBundle\Tests\Security;

use Sententiaregum\Bundle\UserBundle\Security\UserProvider;
use Sententiaregum\Domain\User\User;
use Sententiaregum\Domain\User\UserReadRepositoryInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserProviderTest extends \PHPUnit_Framework_TestCase 
{
    public function testLoadByUsername()
    {
        $userMock = $this->getMockBuilder(User::class)->disableOriginalConstructor()->getMock();
        $userMock
            ->expects($this->any())
            ->method('getUsername')
            ->will($this->returnValue('foobar'));

        $repository = $this->getMock(UserReadRepositoryInterface::class);
        $repository
            ->expects($this->any())
            ->method('findOneByName')
            ->will($this->returnValue($userMock));

        $userProvider = new UserProvider($repository);

        $this->assertSame($userMock, $userProvider->loadUserByUsername('foobar'));
    }

    public function testUserSupport()
    {
        $userProvider = new UserProvider($this->getMock(UserReadRepositoryInterface::class));

        $this->assertTrue($userProvider->supportsClass(User::class));
        $this->assertFalse($userProvider->supportsClass(UserInterface::class));
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\UnsupportedUserException
     */
    public function testThrowUnsupportedExceptionForInvalidUser()
    {
        $mock = $this->getMock(UserInterface::class);

        $userProvider = new UserProvider($this->getMock(UserReadRepositoryInterface::class));
        $userProvider->refreshUser($mock);
    }

    public function testRefreshUser()
    {
        $stub = new User('username', 'password', 'email@example.org');
        $repo = $this->getMock(UserReadRepositoryInterface::class);
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
        $stub = new User('username', 'password', 'email@example.org');
        $repo = $this->getMock(UserReadRepositoryInterface::class);
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
