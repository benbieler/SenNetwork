<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

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
