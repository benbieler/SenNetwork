<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\UserBundle\DependencyInjection;

use Sententiaregum\Bundle\UserBundle\Security\ApiKeyAuthentication;
use Sententiaregum\Bundle\UserBundle\Security\Token;
use Sententiaregum\Bundle\UserBundle\Security\UserProvider;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 */
class SententiaregumUserExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('registration.defaultRoles', $config['registration']['defaultRoles']);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load('security.xml');
        $loader->load('account.xml');
        $loader->load('constraints.xml');

        // compile security classes which will be used at every request to the firewall
        foreach ([ApiKeyAuthentication::class, Token::class, UserProvider::class] as $class) {
            if (!class_exists($class)) {
                throw new \UnexpectedValueException(sprintf('Class %s cannot be found!', $class));
            }

            $this->addClassesToCompile([$class]);
        }
    }
}
