<?php

namespace Sententiaregum\Bundle\RedisMQBundle\DependencyInjection;

use Sententiaregum\Bundle\RedisMQBundle\Service\QueueContext;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class SententiaregumRedisMQExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        foreach ($config['queues'] as $alias => $namespace) {
            $container
                ->register('sen.redis_queue.context.' . $alias, QueueContext::class)
                ->addArgument(new Reference('snc_redis.default'))
                ->addMethodCall('setQueueNamespace', [$namespace]);
        }
    }
}
