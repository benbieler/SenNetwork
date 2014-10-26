<?php
namespace Ma27\SocialNetworkingBundle\Util\Api;

interface PasswordHasherInterface
{
    /**
     * @param string $raw
     * @param integer $cost
     * @return string
     */
    public function create($raw, $cost = 8);

    /**
     * @param string $raw
     * @param string $password
     * @return boolean
     */
    public function verify($raw, $password);
}
