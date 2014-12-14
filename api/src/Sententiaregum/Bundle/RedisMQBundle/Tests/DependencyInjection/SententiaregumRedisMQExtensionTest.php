<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\RedisMQBundle\Tests\DependencyInjection;

use Predis\Client;
use Sententiaregum\Bundle\RedisMQBundle\DependencyInjection\SententiaregumRedisMQExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class SententiaregumRedisMQExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ContainerBuilder
     */
    private $containerBuilder;

    public function setUp()
    {
        $this->containerBuilder = new ContainerBuilder();
        $this->containerBuilder->registerExtension(new SententiaregumRedisMQExtension());

        $this->containerBuilder->setDefinition(
            'snc_redis.default', new Definition(get_class($this->getMockBuilder(Client::class)->disableOriginalConstructor()->getMock()))
        );
    }
    
    public function tearDown()
    {
        $this->containerBuilder = null;
    }

    public function testDependencyBuild()
    {
        $this->containerBuilder->loadFromExtension('sententiaregum_redis_mq', [
            'queues' => [
                'default' => 'foo::bar::'
            ]
        ]);

        $this->containerBuilder->compile();

        $this->assertTrue($this->containerBuilder->hasParameter('sen.redis_queue.context.class'));
        $this->assertTrue(class_exists($this->containerBuilder->getParameter('sen.redis_queue.context.class')));

        $this->assertTrue($this->containerBuilder->hasDefinition('sen.redis_queue.context.default'));
        $this->assertSame(
            $this->containerBuilder->getDefinition('sen.redis_queue.context.default')->getClass(),
            $this->containerBuilder->getParameter('sen.redis_queue.context.class')
        );
    }
}
