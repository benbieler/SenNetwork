<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\CommonBundle\Tests\Compiler;

use Sententiaregum\Bundle\CommonBundle\Compiler\ValidatorPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\Validator\ValidatorBuilder;

class ValidatorPassTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Sententiaregum\Bundle\CommonBundle\Exception\InvalidConfigPathException
     */
    public function testExceptionIfPathIsInvalid()
    {
        new ValidatorPass('invalid path');
    }

    public function testValidatorFileLoad()
    {
        $builder = new ContainerBuilder();

        $builder->setDefinition('validator.builder', new Definition(ValidatorBuilder::class));
        $builder->addCompilerPass(new ValidatorPass(__DIR__ . '/../Fixtures'));
        $builder->compile();

        $def = $builder->getDefinition('validator.builder');
        $this->assertContains(
            ['addXmlMappings', [[dirname(__DIR__) . '/Fixtures/validation/fixture.xml']]],
            $def->getMethodCalls()
        );
    }
}
