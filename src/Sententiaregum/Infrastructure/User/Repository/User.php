<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Infrastructure\User\Repository;

use Doctrine\ORM\EntityRepository;
use Sententiaregum\CoreDomain\User\UserAggregateRepositoryInterface;

/**
 * Concrete implementation of the user repository
 */
class User extends EntityRepository implements UserAggregateRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function findOneByName($username)
    {
        # TODO: Implement findOneByName() method.
    }

    /**
     * {@inheritdoc}
     */
    public function findOneByApiKey($apiKey)
    {
        # TODO: Implement findOneByApiKey() method.
    }
}