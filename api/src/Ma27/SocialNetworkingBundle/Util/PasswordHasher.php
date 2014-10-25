<?php
namespace Ma27\SocialNetworkingBundle\Util;

use Ma27\SocialNetworkingBundle\Util\Api\PasswordHasherInterface;

class PasswordHasher implements PasswordHasherInterface
{
    /**
     * @param string $raw
     * @return string
     */
    public function create($raw)
    {
        $options = ['cost' => 15];
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
