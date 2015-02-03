<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bridge\User\Service;

use Sententiaregum\Bridge\User\DTO\CreateUserDTO;

/**
 * Interface which provides CRUD operations for a user
 */
interface UserInterface
{
    /**
     * @var string
     */
    const USER_CREATED_EVENT = 'sen.domain.user.create';

    /**
     * Creates a user
     *
     * @param CreateUserDTO $createUser
     *
     * @return boolean
     */
    public function create(CreateUserDTO $createUser);
}
