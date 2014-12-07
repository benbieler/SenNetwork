<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle;
use Sensio\Bundle\DistributionBundle\SensioDistributionBundle;
use Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle;
use Sententiaregum\Bundle\CommentBundle\SententiaregumCommentBundle;
use Sententiaregum\Bundle\EntryParsingBundle\SententiaregumEntryParsingBundle;
use Sententiaregum\Bundle\FollowerBundle\SententiaregumFollowerBundle;
use Sententiaregum\Bundle\HashtagsBundle\SententiaregumHashtagsBundle;
use Sententiaregum\Bundle\MicrobloggingBundle\SententiaregumMicrobloggingBundle;
use Sententiaregum\Bundle\RedisMQBundle\SententiaregumRedisMQBundle;
use Sententiaregum\Bundle\UserBundle\SententiaregumUserBundle;
use Snc\RedisBundle\SncRedisBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Bundle\MonologBundle\MonologBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Sententiaregum application kernel
 */
class AppKernel extends Kernel
{
    /**
     * {@inheritdoc}
     */
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
            new SententiaregumHashtagsBundle(),
            new SententiaregumFollowerBundle()
        ];

        if (in_array($this->environment, ['dev','test'])) {
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

    public function getCacheDir()
    {
        if ($this->isVagrantBox()) {
            return '/dev/shm/sen/cache';
        }

        return parent::getCacheDir();
    }

    public function getLogDir()
    {
        if ($this->isVagrantBox()) {
            return '/dev/shm/sen/logs';
        }

        return parent::getLogDir();
    }

    private function isVagrantBox()
    {
        return getenv('VAGRANT') === 'VAGRANT' && is_dir('/dev/shm');
    }
}
