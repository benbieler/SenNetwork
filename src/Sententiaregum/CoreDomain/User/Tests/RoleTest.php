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

use Sententiaregum\CoreDomain\User\Role;

class RoleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Sententiaregum\CoreDomain\User\Exception\UserDomainException
     * @expectedExceptionMessage Invalid role entered: allowed roles are ROLE_USER, ROLE_ADMIN
     */
    public function testThrowsExceptionIfInvalidRoleValueIsGiven()
    {
        new Role('INVALID_ROLE');
    }
}
