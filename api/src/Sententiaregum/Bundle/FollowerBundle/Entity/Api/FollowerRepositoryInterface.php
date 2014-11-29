<?php

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
