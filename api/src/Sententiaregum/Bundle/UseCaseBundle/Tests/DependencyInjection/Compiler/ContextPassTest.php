<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\UseCaseBundle\Tests\DependencyInjection\Compiler;

use Sententiaregum\Bundle\UseCaseBundle\Container\ContextContainer;
use Sententiaregum\Bundle\UseCaseBundle\Context\ContextInterface;
use Sententiaregum\Bundle\UseCaseBundle\DependencyInjection\Compiler\ContextPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class ContextPassTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ContainerBuilder
     */
    private $builder;

    public function setUp()
    {
        $builder = new ContainerBuilder();

        for ($i = 0; $i < 5; $i++) {
            $mock = $this->getMock(ContextInterface::class);
            $taggedDefinition = new Definition(get_class($mock));
            $taggedDefinition->addTag('sen.use_case', ['requiredDTOParams' => null, 'alias' => $i]);

            $builder->setDefinition('use_case_fixture_' . $i, $taggedDefinition);
        }

        $builder->addCompilerPass(new ContextPass());
        $this->builder = $builder;
    }
    
    public function tearDown()
    {
        $this->builder = null;
    }

    public function testAutoContainerConfiguration()
    {
        $this->builder->setDefinition('sen.use_case.contexts', new Definition(ContextContainer::class));
        $this->builder->compile();

        /** @var ContextContainer $contextContainer */
        $contextContainer = $this->builder->get('sen.use_case.contexts');
        $this->assertInstanceOf(ContextContainer::class, $contextContainer);

        $this->assertCount(5, $contextContainer->all());
    }
}
 