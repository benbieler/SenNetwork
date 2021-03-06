<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\MicrobloggingBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     * @throws \InvalidArgumentException
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('sententiaregum_microblogging');

        $rootNode
            ->children()
                ->scalarNode('image_upload_dir')
                    ->isRequired()
                    ->validate()
                    ->always(
                        function ($value) {
                            if (!file_exists($value)) {
                                throw new \InvalidArgumentException('Invalid upload path (' . $value . ') specified');
                            }

                            return $value;
                        }
                    )
                ->end()
            ->end();

        return $treeBuilder;
    }
}
