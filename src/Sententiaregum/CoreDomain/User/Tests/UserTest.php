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
        $user = new User('username', 'password', 'email@example.org');

        $user->updateLastAction(new \DateTime('5 minutes ago'));
        $this->assertTrue($user->isOnline());

        $user->updateLastAction(new \DateTime('1 hour ago'));
        $this->assertFalse($user->isOnline());
    }

    public function testRemoveRole()
    {
        $roleStub = $this->getMockBuilder(Role::class)->disableOriginalConstructor()->getMock();
        $user     = new User('username', 'password', 'email@example.org');

        $user->addRole($roleStub);
        $this->assertTrue($this->assertHasRole($roleStub, $user->getRoles()));

        $user->removeRole($roleStub);
        $this->assertFalse($this->assertHasRole($roleStub, $user->getRoles()));
    }

    public function testSetStringAsPassword()
    {
        $password = '123456';
        $user     = new User(null, $password, null);

        $result = $user->getPassword();

        $this->assertInstanceOf(Password::class, $result);
        $this->assertTrue($result->compare('123456'));
    }

    public function testSetPasswordWithValueObject()
    {
        $password = new Password('1234346');
        $this->assertFalse($password->isHashed());

        $user = new User(null, $password, null);

        $this->assertTrue($user->getPassword()->isHashed());
    }

    /**
     * @expectedException \Sententiaregum\CoreDomain\User\Exception\UserDomainException
     * @expectedExceptionMessage Invalid data type (string and Password are allowed only)!
     */
    public function testSetPasswordWithInvalidDataType()
    {
        new User('username', 10, 'email@example.org');
    }

    /**
     * @expectedException \Sententiaregum\CoreDomain\User\Exception\UserDomainException
     * @expectedExceptionMessage Given password property is empty!
     */
    public function testSetPasswordWithNoPassword()
    {
        new User('username', null, 'email@example.org');
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

    /**
     * @expectedException \Sententiaregum\CoreDomain\User\Exception\UserDomainException
     * @expectedExceptionMessage Email is invalid!
     */
    public function testInvalidEmail()
    {
        new User('admin', 'password', 'invalid email');
    }
}
