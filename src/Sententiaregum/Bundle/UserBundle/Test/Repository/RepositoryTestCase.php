<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\UserBundle\Test\Repository;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Test case for infrastructure objects
 */
abstract class RepositoryTestCase extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected static $entityManager;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    protected static $repository;

    public static function setUpRepositorySet($entityClass, $entityManager = 'default')
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();

        $container             = static::$kernel->getContainer();
        $registry              = $container->get('doctrine');
        static::$entityManager = $registry->getManager($entityManager);
        static::$repository    = static::$entityManager->getRepository($entityClass);
    }
}
