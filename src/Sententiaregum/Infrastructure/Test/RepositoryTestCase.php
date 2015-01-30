<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Infrastructure\Test;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Test case for infrastructure objects
 */
class RepositoryTestCase extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected static $em;

    /**
     * @var \Sententiaregum\Infrastructure\User\Repository\User
     */
    protected static $repository;

    public static function setUpRepositorySet($entityClass)
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();

        $container          = static::$kernel->getContainer();
        static::$em         = $container->get('doctrine.orm.default_entity_manager');
        static::$repository = static::$em->getRepository($entityClass);
    }

    protected function purgeEntities(array $entityClasses)
    {
        $em = static::$em;
        foreach ($entityClasses as $entityClass) {
            $em->createQuery(sprintf("DELETE FROM %s", $entityClass))->execute();
        }
    }
}
