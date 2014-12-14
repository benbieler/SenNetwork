<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\MicrobloggingBundle\Tests\DependencyInjection;

use Doctrine\DBAL\Connection;
use Sententiaregum\Bundle\EntryParsingBundle\Parser\EntryParser;
use Sententiaregum\Bundle\HashtagsBundle\Entity\TagRepository;
use Sententiaregum\Bundle\MicrobloggingBundle\DependencyInjection\SententiaregumMicrobloggingExtension;
use Sententiaregum\Bundle\RedisMQBundle\Service\QueueContext;
use Sententiaregum\Bundle\UserBundle\Entity\UserRepository;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Validator\Validator\RecursiveValidator;

class SententiaregumMicrobloggingExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ContainerBuilder
     */
    private $containerBuilder;

    public function setUp()
    {
        $this->containerBuilder = new ContainerBuilder();
        $this->containerBuilder->registerExtension(new SententiaregumMicrobloggingExtension());

        $this->containerBuilder->setDefinition(
            'security.context',
            new Definition(
                get_class($this->getMockBuilder(SecurityContext::class)->disableOriginalConstructor()->getMock())
            )
        );

        $this->containerBuilder->setDefinition(
            'validator',
            new Definition(
                get_class($this->getMockBuilder(RecursiveValidator::class)->disableOriginalConstructor()->getMock())
            )
        );

        $this->containerBuilder->setDefinition(
            'sen.redis_queue.context.default',
            new Definition(
                get_class($this->getMockBuilder(QueueContext::class)->disableOriginalConstructor()->getMock())
            )
        );

        $this->containerBuilder->setDefinition(
            'database_connection',
            new Definition(get_class($this->getMockBuilder(Connection::class)->disableOriginalConstructor()->getMock()))
        );

        $this->containerBuilder->setDefinition(
            'sen_parser.entry_parser',
            new Definition(get_class($this->getMockBuilder(EntryParser::class)->disableOriginalConstructor()->getMock()))
        );

        $this->containerBuilder->setDefinition(
            'sen.hashtags.repository',
            new Definition(
                get_class($this->getMockBuilder(TagRepository::class)->disableOriginalConstructor()->getMock())
            )
        );

        $this->containerBuilder->setDefinition(
            'sen.user.repository',
            new Definition(
                get_class($this->getMockBuilder(UserRepository::class)->disableOriginalConstructor()->getMock())
            )
        );
    }
    
    public function tearDown()
    {
        $this->containerBuilder = null;
    }

    public function testDependencyBuild()
    {
        $this->containerBuilder->loadFromExtension(
            'sententiaregum_microblogging', ['image_upload_dir' => __DIR__]
        );

        $this->containerBuilder->compile();

        foreach (['sen.microblog.posts.controller', 'sen.microblog.repository', 'sen.microblog.write_entry'] as $element) {
            $this->assertTrue($this->containerBuilder->hasParameter($element . '.class'));
            $this->assertTrue(class_exists($this->containerBuilder->getParameter($element . '.class')));
            $this->assertTrue($this->containerBuilder->hasDefinition($element));

            $this->assertSame(
                $this->containerBuilder->getDefinition($element)->getClass(),
                $this->containerBuilder->getParameter($element . '.class')
            );
        }
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     * @expectedExceptionMessage Invalid configuration for path "sententiaregum_microblogging.image_upload_dir": Invalid upload path () specified
     */
    public function testDependencyBuildWithInvalidConfig()
    {
        $this->containerBuilder->loadFromExtension('sententiaregum_microblogging', ['image_upload_dir' => null]);
        $this->containerBuilder->compile();
    }
}
