<?php
namespace Ma27\SocialNetworkingBundle\Util\Api;

interface PasswordHasherInterface
{
    /**
     * @param string $raw
     * @return string
     */
    public function create($raw);

    /**
     * @param string $raw
     * @param string $password
     * @return boolean
     */
    public function verify($raw, $password);
}
