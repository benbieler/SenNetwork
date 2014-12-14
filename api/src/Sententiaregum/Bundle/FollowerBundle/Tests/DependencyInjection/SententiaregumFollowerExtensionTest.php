<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\FollowerBundle\Tests\DependencyInjection;

use Doctrine\DBAL\Connection;
use Sententiaregum\Bundle\FollowerBundle\DependencyInjection\SententiaregumFollowerExtension;
use Sententiaregum\Bundle\UserBundle\Entity\Api\UserRepositoryInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class SententiaregumFollowerExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ContainerBuilder
     */
    private $containerBuilder;

    public function setUp()
    {
        $this->containerBuilder = new ContainerBuilder();
        $this->containerBuilder->setDefinition(
            'database_connection',
            new Definition(get_class($this->getMockBuilder(Connection::class)->disableOriginalConstructor()->getMock()))
        );
        $this->containerBuilder->setDefinition(
            'sen.user.repository',
            new Definition(
                get_class($this->getMockBuilder(UserRepositoryInterface::class)->disableOriginalConstructor()->getMock())
            )
        );

        $this->containerBuilder->registerExtension(new SententiaregumFollowerExtension());
    }
    
    public function tearDown()
    {
        $this->containerBuilder = null;
    }

    public function testDependencyBuild()
    {
        $this->containerBuilder->loadFromExtension('sententiaregum_follower');
        $this->containerBuilder->compile();

        foreach (['sen.follower.repository', 'sen.follower.controller', 'sen.follower.advice'] as $id) {
            $this->assertTrue($this->containerBuilder->hasDefinition($id));
            $this->assertTrue(class_exists($this->containerBuilder->getParameter($id . '.class')));
            $this->assertTrue($this->containerBuilder->hasParameter($id . '.class'));

            $this->assertSame(
                $this->containerBuilder->getDefinition($id)->getClass(),
                $this->containerBuilder->getParameter($id . '.class')
            );
        }
    }
}
