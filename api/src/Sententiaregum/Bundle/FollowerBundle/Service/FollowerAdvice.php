<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\FollowerBundle\Service;

use Sententiaregum\Bundle\FollowerBundle\Entity\Api\AdvancedFollowerRepositoryInterface;
use Sententiaregum\Bundle\FollowerBundle\Service\Api\FollowerAdviceInterface;
use Sententiaregum\Bundle\UserBundle\Entity\Api\UserInterface;
use Sententiaregum\Bundle\UserBundle\Entity\Api\UserRepositoryInterface;

class FollowerAdvice implements FollowerAdviceInterface
{
    /**
     * @var AdvancedFollowerRepositoryInterface
     */
    private $followerRepository;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @param AdvancedFollowerRepositoryInterface $followerRepositoryInterface
     * @param UserRepositoryInterface $userRepositoryInterface
     */
    public function __construct(
        AdvancedFollowerRepositoryInterface $followerRepositoryInterface,
        UserRepositoryInterface $userRepositoryInterface)
    {
        $this->followerRepository = $followerRepositoryInterface;
        $this->userRepository = $userRepositoryInterface;
    }

    /**
     * @param integer $userId
     * @param integer $length
     * @return \Sententiaregum\Bundle\UserBundle\Entity\Api\UserInterface[]
     */
    public function createAdviceList($userId, $length = 5)
    {
        if (!$this->followerRepository->hasFollowers($userId)) {
            return $this->filterResult($this->userRepository->createRandomUserList($length), $userId);
        }

        $result = [];
        foreach ($this->followerRepository->findFollowingByFollowingOfUser($userId, $length) as $resultItem) {
            $result[] = $this->userRepository->findById($resultItem['follower_id']);
        }
        if (count($result) <= 0) {
            $result = $this->userRepository->createRandomUserList($length);
        }

        return $this->filterResult($result, $userId);
    }

    /**
     * @param \Sententiaregum\Bundle\UserBundle\Entity\Api\UserInterface[] $users
     * @param integer $userId
     * @return \Sententiaregum\Bundle\UserBundle\Entity\Api\UserInterface[]
     */
    private function filterResult(array $users, $userId)
    {
        return array_filter(
            $users,
            function (UserInterface $value) use ($userId) {
                return $value->getId() !== $userId;
            }
        );
    }
}
