<?php
namespace Ma27\SocialNetworkingBundle\Service\Api;

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
