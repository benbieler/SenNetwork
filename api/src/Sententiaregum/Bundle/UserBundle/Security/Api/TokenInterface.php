<?php

namespace Sententiaregum\Bundle\UserBundle\Security\Api;

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
