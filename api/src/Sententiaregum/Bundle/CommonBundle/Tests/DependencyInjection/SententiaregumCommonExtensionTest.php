<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\CommonBundle\Tests\DependencyInjection;

use Sententiaregum\Bundle\CommonBundle\DependencyInjection\SententiaregumCommonExtension;
use Sententiaregum\Bundle\CommonBundle\EventListener\RestExceptionHandler;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SententiaregumCommonExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ContainerBuilder
     */
    private $containerBuilder;

    public function setUp()
    {
        $this->containerBuilder = new ContainerBuilder();
        $this->containerBuilder->registerExtension(new SententiaregumCommonExtension());
    }
    
    public function tearDown()
    {
        $this->containerBuilder = null;
    }

    public function testDependencyBuild()
    {
        $this->containerBuilder->loadFromExtension('sententiaregum_common');
        $this->containerBuilder->compile();

        $this->assertTrue($this->containerBuilder->hasDefinition('sen.common.rest_exception_handler'));

        $this->assertSame($this->containerBuilder->getParameter('sen.common.rest_exception_handler.class'), RestExceptionHandler::class);
    }
}
