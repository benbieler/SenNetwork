<?php

namespace Sententiaregum\Bundle\UserBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('sententiaregum_user');

        $rootNode
            ->children()
                ->arrayNode('api_key_authentication')
                    ->canBeEnabled()
                    ->children()
                        ->scalarNode('credential_verify_service')
                            ->defaultValue('sen.user.service.authentication')
                        ->end()
                        ->scalarNode('api_token_generator')
                            ->defaultValue('sen.user.service.api_token_generator')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('user_registration')
                    ->canBeEnabled()
                    ->children()
                        ->arrayNode('default_registration_roles')
                            ->prototype('scalar')->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('service')
                    ->children()
                        ->scalarNode('user_crud')
                            ->defaultValue('sen.user.service.crud')
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
