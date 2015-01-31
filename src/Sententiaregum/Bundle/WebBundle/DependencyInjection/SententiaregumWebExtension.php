<?php

namespace Sententiaregum\Bundle\WebBundle\DependencyInjection;

use Sententiaregum\Bundle\WebBundle\Service\Value\FormReference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class SententiaregumWebExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        if ($config['form_templates']['enabled']) {
            $loader->load('form_templating.xml');

            if ($container->hasDefinition('sen.web.template_service')) {
                $references = [];
                foreach ($config['form_templates']['associations'] as $formAlias => $templateName) {
                    $references[] = new FormReference($formAlias, $templateName);
                }

                $definition = $container->getDefinition('sen.web.template_service');
                $definition->addMethodCall('appendFormSet', $references);
            }
        }
    }
}
