<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\UserBundle\Tests\Command;

use Sententiaregum\Bundle\UserBundle\Command\CreateAdminUserCommand;
use Sententiaregum\Bundle\UserBundle\Entity\Api\UserInterface;
use Sententiaregum\Bundle\UserBundle\Entity\Api\UserRepositoryInterface;
use Sententiaregum\Bundle\UserBundle\Service\CreateAccountInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\Container;

class CreateAdminUserCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Application
     */
    private $application;

    public function setUp()
    {
        $this->application = new Application();
    }
    
    public function tearDown()
    {
        $this->application = null;
    }

    public function testAdminUserCreation()
    {
        $stub = $this->getMock(UserRepositoryInterface::class);
        $stub
            ->expects($this->any())
            ->method('create')
            ->will($this->returnValue(
                $this->getMock(UserInterface::class)
            ));

        $service = $this->getMock(CreateAccountInterface::class);
        $service
            ->expects($this->any())
            ->method('validateInput')
            ->will($this->returnValue([]));

        $container = new Container();
        $container->setParameter('registration.defaultRoles', ['ROLE_USER']);
        $container->set('sen.user.repository', $stub);
        $container->set('sen.user.create_account', $service);

        $cmd = new CreateAdminUserCommand();
        $cmd->setContainer($container);
        $this->application->add($cmd);

        $cmd = $this->application->find('sen:user:create-admin');
        $this->assertNotNull($cmd);

        $tester =  new CommandTester($cmd);
        $this->assertSame(0, $tester->execute([
            '--name' => 'John Doe',
            '--password' => '123456',
            '--email' => 'johndoe@example.org'
        ]));
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Invalid data entered: error message
     */
    public function testAdminUserCreationWithInvalidCredentials()
    {
        $stub = $this->getMock(UserRepositoryInterface::class);
        $stub
            ->expects($this->any())
            ->method('create')
            ->will($this->returnValue(
                $this->getMock(UserInterface::class)
            ));

        $service = $this->getMock(CreateAccountInterface::class);
        $service
            ->expects($this->any())
            ->method('validateInput')
            ->will($this->returnValue(['error message']));

        $container = new Container();
        $container->setParameter('registration.defaultRoles', ['ROLE_USER']);
        $container->set('sen.user.repository', $stub);
        $container->set('sen.user.create_account', $service);

        $cmd = new CreateAdminUserCommand();
        $cmd->setContainer($container);
        $this->application->add($cmd);

        $cmd = $this->application->find('sen:user:create-admin');
        $this->assertNotNull($cmd);

        $tester =  new CommandTester($cmd);
        $tester->execute([
            '--name' => 'John Doe',
            '--password' => '123456',
            '--email' => 'johndoe@example.org'
        ]);
    }
}
