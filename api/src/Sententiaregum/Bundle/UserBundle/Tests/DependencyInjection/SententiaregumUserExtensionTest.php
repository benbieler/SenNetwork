<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\UserBundle\Tests\DependencyInjection;

use Doctrine\DBAL\Connection;
use Sententiaregum\Bundle\UserBundle\DependencyInjection\SententiaregumUserExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\Validator\Validator\RecursiveValidator;

class SententiaregumUserExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ContainerBuilder
     */
    private $containerBuilder;

    public function setUp()
    {
        $this->containerBuilder = new ContainerBuilder();
        $this->containerBuilder->registerExtension(new SententiaregumUserExtension());

        $this->containerBuilder->setDefinition(
            'database_connection',
            new Definition(
                get_class($this->getMockBuilder(Connection::class)->disableOriginalConstructor()->getMock())
            )
        );
        $this->containerBuilder->setDefinition(
            'validator',
            new Definition(
                get_class($this->getMockBuilder(RecursiveValidator::class)->disableOriginalConstructor()->getMock())
            )
        );
    }
    
    public function tearDown()
    {
        $this->containerBuilder = null;
    }

    public function testDependencyBuild()
    {
        $this->containerBuilder->loadFromExtension('sententiaregum_user', [
            'registration' => [
                'defaultRoles' => []
            ]
        ]);
        $this->containerBuilder->compile();

        foreach (
            ['sen.account.controller', 'sen.user.repository', 'sen.user.create_account', 'sen.user.constraint.unique_user',
            'sen.user.constraint.unique_email', 'sen.token.controller', 'sen.security.api_key_auth', 'sen.security.token',
            'sen.user.util.hasher']
            as $element
        ) {
            $this->assertTrue($this->containerBuilder->hasParameter($element . '.class'));
            $this->assertTrue(class_exists($this->containerBuilder->getParameter($element . '.class')));
            $this->assertTrue($this->containerBuilder->hasDefinition($element));

            $this->assertSame(
                $this->containerBuilder->getDefinition($element)->getClass(),
                $this->containerBuilder->getParameter($element . '.class')
            );
        }
    }
}
