<?php

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
