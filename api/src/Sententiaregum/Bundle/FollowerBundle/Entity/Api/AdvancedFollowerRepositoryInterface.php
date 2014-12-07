<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\FollowerBundle\Entity\Api;

interface AdvancedFollowerRepositoryInterface extends FollowerRepositoryInterface
{
    /**
     * @param integer $userId
     * @return boolean
     */
    public function hasFollowers($userId);

    /**
     * @param integer $userId
     * @param integer $limit
     * @return integer[]
     */
    public function findFollowingByFollowingOfUser($userId, $limit = null);

    /**
     * @return void
     */
    public function flush();
}
