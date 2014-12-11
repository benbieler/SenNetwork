<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\UseCaseBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContextPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     *
     * @api
     */
    public function process(ContainerBuilder $container)
    {
        $contextStorage = $container->getDefinition('sen.use_case.contexts');

        foreach ($container->findTaggedServiceIds('sen.use_case') as $serviceId => $attributes) {
            foreach ($attributes as $tagAttributes) {
                $resolver = new OptionsResolver();

                if (isset($tagAttributes['requiredDTOParams'])) {
                    $resolver->setRequired(explode('|', $tagAttributes['requiredDTOParams']));
                }

                $contextStorage->addMethodCall(
                    'add',
                    [$tagAttributes['alias'], new Reference($serviceId), $resolver]
                );
            }
        }
    }
}
