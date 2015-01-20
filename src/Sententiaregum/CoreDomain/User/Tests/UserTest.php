<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\CoreDomain\User\Tests;

use Sententiaregum\CoreDomain\User\DTO\AuthDTO;
use Sententiaregum\CoreDomain\User\Event\AuthEvent;
use Sententiaregum\CoreDomain\User\Password;
use Sententiaregum\CoreDomain\User\Role;
use Sententiaregum\CoreDomain\User\User;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class UserTest extends \PHPUnit_Framework_TestCase
{
    public function testIsOnline()
    {
        $user = new User();

        $user->setLastAction(new \DateTime('5 minutes ago'));
        $this->assertTrue($user->isOnline());

        $user->setLastAction(new \DateTime('1 hour ago'));
        $this->assertFalse($user->isOnline());
    }

    public function testRemoveRole()
    {
        $roleStub = $this->getMockBuilder(Role::class)->disableOriginalConstructor()->getMock();
        $user     = new User();

        $user->addRole($roleStub);
        $this->assertTrue($this->assertHasRole($roleStub, $user->getRoles()));

        $user->removeRole($roleStub);
        $this->assertFalse($this->assertHasRole($roleStub, $user->getRoles()));
    }

    public function testSetTokenForAlreadyAuthenticatedUser()
    {
        $user = new User();
        $token = $user->createToken()->getToken()->getApiKey();
        $this->assertSame($token, $user->getToken()->getApiKey());
        $this->assertSame(200, strlen($user->getToken()->getApiKey()));
        $user->createToken();
        $this->assertSame($token, $user->getToken()->getApiKey());
    }

    public function testSetStringAsPassword()
    {
        $password = '123456';
        $user     = new User();

        $user->setPassword($password);
        $result = $user->getPassword();

        $this->assertInstanceOf(Password::class, $result);
        $this->assertTrue($result->compare('123456'));
    }

    public function testSetPasswordWithValueObject()
    {
        $password = new Password('1234346');
        $user     = new User();

        $this->assertFalse($password->isHashed());

        $user->setPassword($password);
        $this->assertTrue($user->getPassword()->isHashed());
    }

    public function testUserVerify()
    {
        $user1 = new User();
        $user1->setPassword('123456');
        $user1->setUsername('admin');

        $dto  = new AuthDTO('admin', '123456');
        $mock = $this->getMock(EventDispatcherInterface::class);
        $mock
            ->expects($this->any())
            ->method('dispatch')
            ->will($this->returnArgument(1));

        $result = $user1->authenticate($dto, $mock);
        $this->assertInstanceOf(AuthEvent::class, $result);

        $this->assertNull($result->getFailReason());
    }

    public function testFailedUserAuth()
    {
        $user1 = new User();
        $user1->setPassword('123456');
        $user1->setUsername('admin');

        $dto  = new AuthDTO('admin', 'foo');
        $mock = $this->getMock(EventDispatcherInterface::class);

        $result = $user1->authenticate($dto, $mock);
        $this->assertInstanceOf(AuthEvent::class, $result);
        $this->assertSame('Invalid credentials!', $result->getFailReason());
    }

    /**
     * @expectedException \Sententiaregum\CoreDomain\User\Exception\UserDomainException
     * @expectedExceptionMessage Invalid data type (string and Password are allowed only)!
     */
    public function testSetPasswordWithInvalidDataType()
    {
        (new User())->setPassword(10);
    }

    /**
     * @expectedException \Sententiaregum\CoreDomain\User\Exception\UserDomainException
     * @expectedExceptionMessage Given password property is empty!
     */
    public function testSetPasswordWithNoPassword()
    {
        (new User())->setPassword(null);
    }

    /**
     * Simple assertion because in array does not work properly with objects
     *
     * @param Role $role
     * @param array $stack
     *
     * @return boolean
     */
    private function assertHasRole(Role $role, array $stack)
    {
        foreach ($stack as $item) {
            if ($role === $item) {
                return true;
            }
        }

        return false;
    }
}
