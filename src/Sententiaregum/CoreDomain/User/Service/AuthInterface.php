<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\CoreDomain\User\Service;

use Sententiaregum\CoreDomain\User\DTO\AuthDTO;

/**
 * Contract of an authentication domain service
 */
interface AuthInterface
{
    /**
     * Authenticates a user
     *
     * @param AuthDTO $credentials
     *
     * @return \Sententiaregum\CoreDomain\User\Event\AuthEvent
     */
    public function authenticateUser(AuthDTO $credentials);
}
