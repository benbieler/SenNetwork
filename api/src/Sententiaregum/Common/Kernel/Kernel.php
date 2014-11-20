<?php

namespace Sententiaregum\Common\Kernel;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle;
use Sensio\Bundle\DistributionBundle\SensioDistributionBundle;
use Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle;
use Sententiaregum\Bundle\CommentBundle\SententiaregumCommentBundle;
use Sententiaregum\Bundle\EntryParsingBundle\SententiaregumEntryParsingBundle;
use Sententiaregum\Bundle\HashtagsBundle\SententiaregumHashtagsBundle;
use Sententiaregum\Bundle\MicrobloggingBundle\SententiaregumMicrobloggingBundle;
use Sententiaregum\Bundle\RedisMQBundle\SententiaregumRedisMQBundle;
use Sententiaregum\Bundle\UserBundle\SententiaregumUserBundle;
use Snc\RedisBundle\SncRedisBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Bundle\MonologBundle\MonologBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel as SymfonyKernel;

/**
 * Default kernel implementation of this application
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

            // redis
            new SncRedisBundle(),
            new SententiaregumRedisMQBundle(),

            // application bundles
            new SententiaregumUserBundle(),
            new SententiaregumMicrobloggingBundle(),
            new SententiaregumCommentBundle(),
            new SententiaregumEntryParsingBundle(),
            new SententiaregumHashtagsBundle()
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
