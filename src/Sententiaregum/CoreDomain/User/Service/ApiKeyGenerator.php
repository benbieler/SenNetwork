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

use Sententiaregum\CoreDomain\User\Exception\UserDomainException;

/**
 * Implementation of an api key generator using openssl
 */
class ApiKeyGenerator implements ApiKeyGeneratorInterface
{
    /**
     * {@inheritdoc}
     *
     * @throws \Sententiaregum\CoreDomain\User\Exception\UserDomainException If the openssl generation fails
     */
    public function generate()
    {
        $key = openssl_random_pseudo_bytes(100);
        if (!$key) {
            throw new UserDomainException('openssl_random_pseudo_bytes(): returns false since something went wrong');
        }

        return bin2hex($key);
    }
}
