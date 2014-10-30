<?php
namespace Ma27\SocialNetworkingBundle\Security\Api;

interface TokenInterface
{
    /**
     * @return string
     */
    public function generateToken();

    /**
     * @param $id
     * @return string
     */
    public function storeToken($id);
}
