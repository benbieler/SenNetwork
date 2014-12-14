<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\EntryParsingBundle\Tests\DependencyInjection;

use Sententiaregum\Bundle\EntryParsingBundle\DependencyInjection\SententiaregumEntryParsingExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SententiaregumEntryParsingExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ContainerBuilder
     */
    private $container;

    public function setUp()
    {
        $this->container = new ContainerBuilder();
        $this->container->registerExtension(new SententiaregumEntryParsingExtension());
    }

    public function tearDown()
    {
        $this->container = null;
    }

    public function testExtensionBuilder()
    {
        $this->container->loadFromExtension('sententiaregum_entry_parsing', [
            'name_delimiter' => '@',
            'tag_delimiter'  => '#'
        ]);

        $this->container->compile();

        $this->assertTrue($this->container->hasDefinition('sen_parser.entry_parser'));
        $this->assertSame($this->container->getParameter('name_delimiter'), '@');
        $this->assertSame($this->container->getParameter('tag_delimiter'), '#');
        $this->assertTrue($this->container->getParameter('strip_delimiter'));
    }
}
