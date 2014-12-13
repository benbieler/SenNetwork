<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\UseCaseBundle\Tests\DependencyInjection;

use Sententiaregum\Bundle\UseCaseBundle\Container\ContextContainer;
use Sententiaregum\Bundle\UseCaseBundle\Context\Container\Container;
use Sententiaregum\Bundle\UseCaseBundle\DependencyInjection\SententiaregumUseCaseExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SententiaregumUseCaseExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testContainerCompilationWithUseCaseExtension()
    {
        $container = new ContainerBuilder();
        $container->registerExtension(new SententiaregumUseCaseExtension());
        $container->loadFromExtension('sententiaregum_use_case');

        $container->compile();

        $contextContainer = $container->get('sen.use_case.contexts');
        $this->assertInstanceOf(ContextContainer::class, $contextContainer);

        $contextRunner = $container->get('sen.use_case.container');
        $this->assertInstanceOf(Container::class, $contextRunner);
    }
}
