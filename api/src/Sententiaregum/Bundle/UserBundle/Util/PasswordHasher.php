<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\UserBundle\Util;

use Sententiaregum\Bundle\UserBundle\Util\Api\PasswordHasherInterface;

class PasswordHasher implements PasswordHasherInterface
{
    /**
     * @param string $raw
     * @param integer $cost
     * @return string
     */
    public function create($raw, $cost = 8)
    {
        $options = ['cost' => (integer) $cost];
        return password_hash($raw, PASSWORD_BCRYPT, $options);
    }

    /**
     * @param string $raw
     * @param string $password
     * @return boolean
     */
    public function verify($raw, $password)
    {
        return password_verify($raw, $password);
    }
}
