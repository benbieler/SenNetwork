<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\CoreDomain\User;

/**
 * Repository contract which is a responsible to load object lists of the user aggregate
 */
interface UserAggregateRepositoryInterface
{
    /**
     * Searches a user by its username
     *
     * @param string $username
     *
     * @return User
     */
    public function findOneByName($username);

    /**
     * Searches a user by its api key
     *
     * @param string $apiKey
     *
     * @return User
     */
    public function findOneByApiKey($apiKey);
}
