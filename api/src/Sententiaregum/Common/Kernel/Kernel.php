<?php
namespace Sententiaregum\Common\Kernel;

use Sensio\Bundle\DistributionBundle\SensioDistributionBundle;
use Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle;
use Sententiaregum\Bundle\MicrobloggingBundle\SententiaregumMicrobloggingBundle;
use Sententiaregum\Bundle\UserBundle\SententiaregumUserBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Bundle\MonologBundle\MonologBundle;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel as SymfonyKernel;

/**
 * Default kernel implementation of the application
 */
abstract class Kernel extends SymfonyKernel
{
    public function registerBundles()
    {
        $bundles = [
            // symfony 2 bundles
            new FrameworkBundle(),
            new SecurityBundle(),
            new MonologBundle(),

            // doctrine bundles
            new DoctrineBundle(),
            new DoctrineMigrationsBundle(),

            // application bundles
            new SententiaregumUserBundle(),
            new SententiaregumMicrobloggingBundle()
        ];

        if (in_array($this->environment, ['dev', 'test'])) {
            $bundles = array_merge(
                $bundles,
                [
                    new SensioDistributionBundle(),
                    new SensioGeneratorBundle()
                ]
            );
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        return $loader->load($this->getRootDir() . '/config/config_' . $this->environment . '.yml');
    }
}
