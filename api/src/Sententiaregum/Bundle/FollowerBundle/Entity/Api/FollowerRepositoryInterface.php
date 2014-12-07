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

use Sententiaregum\Bundle\FollowerBundle\Entity\Follower;

interface FollowerRepositoryInterface
{
    /**
     * @param Follower $relationShip
     * @return void
     */
    public function createRelation(Follower $relationShip);

    /**
     * @param integer $userId
     * @return Follower[]
     */
    public function findFollowingByUserId($userId);

    /**
     * @param Follower $follower
     * @return void
     */
    public function dropRelation(Follower $follower);
}
