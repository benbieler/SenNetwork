<?php

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
