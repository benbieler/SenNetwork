<?php

namespace Sententiaregum\Bundle\FollowerBundle\Service\Api;

interface FollowerAdviceInterface
{
    /**
     * @param integer $userId
     * @param integer $length
     * @return \Sententiaregum\Bundle\UserBundle\Entity\Api\UserInterface[]
     */
    public function createAdviceList($userId, $length = 5);
}
