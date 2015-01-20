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
interface UserAggregateRepository
{
    /**
     * Searches a user by its string
     *
     * @param string $username
     *
     * @return User
     */
    public function findOneByName($username);
}
