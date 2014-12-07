<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\FollowerBundle\Entity;

class Follower
{
    /**
     * @var integer
     */
    private $user;

    /**
     * @var integer
     */
    private $following;

    /**
     * @return int
     */
    public function getFollowing()
    {
        return $this->following;
    }

    /**
     * @param int $following
     * @return $this
     */
    public function setFollowing($following)
    {
        $this->following = (integer) $following;
        return $this;
    }

    /**
     * @return int
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param int $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = (integer) $user;
        return $this;
    }
}
