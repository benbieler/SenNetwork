<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\UserBundle\Tests\Service;

use Doctrine\ORM\EntityManagerInterface;
use Sententiaregum\Bundle\UserBundle\DTO\CreateUserDTO;
use Sententiaregum\Bundle\UserBundle\Service\User;
use Sententiaregum\Domain\User\Role;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class UserTest extends \PHPUnit_Framework_TestCase 
{
    public function testCreateUser()
    {
        $dto = new CreateUserDTO('admin', 'password', 'admin@example.org', [new Role(Role::USER)]);

        $entityManager = $this->getMock(EntityManagerInterface::class);
        $entityManager->expects($this->any())->method('persist');

        $dispatcher = $this->getMock(EventDispatcherInterface::class);
        $dispatcher->expects($this->any())->method('dispatch');
        $service = new User($entityManager, $dispatcher);

        $this->assertTrue($service->create($dto));
    }
}
