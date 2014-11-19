<?php

namespace Sententiaregum\Bundle\EntryParsingBundle\DependencyInjection;

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
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('sententiaregum_entry_parsing');

        $rootNode
            ->children()
                ->scalarNode('tag_delimiter')->isRequired()->end()
                ->scalarNode('name_delimiter')->isRequired()->end()
                ->booleanNode('strip_delimiter')->defaultValue(false)->end()
            ->end();

        return $treeBuilder;
    }
}
