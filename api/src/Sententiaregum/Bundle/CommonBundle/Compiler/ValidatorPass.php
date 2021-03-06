<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\CommonBundle\Compiler;

use Sententiaregum\Bundle\CommonBundle\Exception\InvalidConfigPathException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\Finder\Finder;

class ValidatorPass implements CompilerPassInterface
{
    /**
     * @var string
     */
    private $configDir;

    /**
     * @param string $confDir
     * @throws InvalidConfigPathException
     */
    public function __construct($confDir)
    {
        if (!file_exists($confDir)) {
            throw new InvalidConfigPathException;
        }

        $this->configDir = $confDir;
    }

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        try {
            $validatorBuilder = $container->getDefinition('validator.builder');
        } catch (InvalidArgumentException $e) {
            return;
        }

        $validatorFiles = [];
        $finder = new Finder();

        foreach ($finder->files()->in($this->configDir . '/validation') as $file) {
            $validatorFiles[] = $file->getRealPath();
        }

        $validatorBuilder->addMethodCall('addXmlMappings', [$validatorFiles]);
    }
}
