<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\UserBundle\Service;

use Sententiaregum\Bundle\UserBundle\Entity\Api\UserInterface;

interface CreateAccountInterface
{
    /**
     * @param UserInterface $user
     * @return void
     */
    public function persist(UserInterface $user);

    /**
     * @param UserInterface $user
     * @return string[]
     */
    public function validateInput(UserInterface $user);
}
