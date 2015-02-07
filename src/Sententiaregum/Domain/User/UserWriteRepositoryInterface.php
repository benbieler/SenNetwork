<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Domain\User;

/**
 * Interface which provides write methods for the user aggregate
 */
interface UserWriteRepositoryInterface
{
    /**
     * Adds a user to the repository
     *
     * @param User $user
     *
     * @return $this
     */
    public function add(User $user);

    /**
     * Updates a user
     *
     * @param User $user
     *
     * @return $this
     */
    public function update(User $user);

    /**
     * Deletes a user
     *
     * @param User $user
     *
     * @return $this
     */
    public function delete(User $user);
}
