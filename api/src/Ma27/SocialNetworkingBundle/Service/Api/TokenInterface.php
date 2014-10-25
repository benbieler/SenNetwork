<?php
namespace Ma27\SocialNetworkingBundle\Service\Api;

interface TokenInterface
{
    /**
     * @return string
     */
    public function generateToken();

    /**
     * @param $token
     * @param $id
     * @return boolean
     */
    public function storeToken($token, $id);
}
 