<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\HashtagsBundle\Tests\DependencyInjection;

use Doctrine\DBAL\Connection;
use Sententiaregum\Bundle\HashtagsBundle\DependencyInjection\SententiaregumHashtagsExtension;
use Sententiaregum\Bundle\HashtagsBundle\Entity\Api\TagRepositoryInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class SententiaregumHashtagsExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ContainerBuilder
     */
    private $containerBuilder;

    public function setUp()
    {
        $this->containerBuilder = new ContainerBuilder();
        $this->containerBuilder->registerExtension(new SententiaregumHashtagsExtension());
        $this->containerBuilder->setDefinition(
            'database_connection',
            new Definition(get_class($this->getMockBuilder(Connection::class)->disableOriginalConstructor()->getMock()))
        );
    }
    
    public function tearDown()
    {
        $this->containerBuilder = null;
    }

    public function testDependencyBuild()
    {
        $this->containerBuilder->loadFromExtension('sententiaregum_hashtags');
        $this->containerBuilder->compile();

        $this->assertTrue($this->containerBuilder->hasParameter('sen.hashtags.repository.class'));
        $this->assertTrue($this->containerBuilder->hasDefinition('sen.hashtags.repository'));
    }
}
