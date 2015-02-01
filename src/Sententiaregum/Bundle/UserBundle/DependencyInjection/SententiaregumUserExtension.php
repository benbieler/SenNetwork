<?php

namespace Sententiaregum\Bundle\UserBundle\DependencyInjection;

use Sententiaregum\Bridge\User\Service\AuthenticationInterface;
use Sententiaregum\CoreDomain\User\Service\ApiKeyGeneratorInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class SententiaregumUserExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('user.xml');

        if ($config['api_key_authentication']['enabled']) {
            $loader->load('api_key.xml');

            $apiKeyDefinition = $container->getDefinition($config['api_key_authentication']['api_token_generator']);
            if (!in_array(ApiKeyGeneratorInterface::class, class_implements($apiKeyDefinition->getClass()))) {
                throw new \InvalidArgumentException(
                    sprintf(
                        'The token generator %s must implement interface %s!',
                        $apiKeyDefinition->getClass(),
                        ApiKeyGeneratorInterface::class
                    )
                );
            }
            $container->setAlias('sen.user.security.api_key', $config['api_key_authentication']['api_token_generator']);

            $loader->load('authentication.xml');

            $authDefinition = $container->getDefinition($config['api_key_authentication']['credential_verify_service']);
            if (!in_array(AuthenticationInterface::class, class_implements($authDefinition->getClass()))) {
                throw new \InvalidArgumentException(
                    sprintf(
                        'The api authenticator %s must implement %s!',
                        $authDefinition->getClass(),
                        AuthenticationInterface::class
                    )
                );
            }

            $container->setAlias(
                'sen.user.auth.api_key_auth',
                $config['api_key_authentication']['credential_verify_service']
            );
        }
    }
}
