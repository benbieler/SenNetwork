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

use Sententiaregum\CoreDomain\User\Password;

class PasswordTest extends \PHPUnit_Framework_TestCase
{
    public function testPasswordVerify()
    {
        $password = new Password('123456');
        $this->assertFalse($password->isHashed());
    }

    public function testAutomatePasswordHash()
    {
        $password = new Password('123456', true);
        $this->assertTrue($password->isHashed());
    }

    public function testHashingByHashedPassword()
    {
        $password = new Password('123456', true);
        $hash = $password->getHash();

        $password->hash();
        $this->assertSame($hash, $password->getHash());
    }

    public function testPasswordGetter()
    {
        $rawPassword = '123456';
        $password    = new Password($rawPassword);

        $this->assertSame($rawPassword, $password->getPassword());
    }

    public function testPasswordToStringConversion()
    {
        $password = new Password('123456');
        $this->assertSame('123456', (string) $password);
    }

    public function testPasswordComparison()
    {
        $password = new Password('123456', true);
        $this->assertTrue($password->compare('123456'));
    }
}
