<?php

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
