<?php

namespace Sententiaregum\Bundle\FollowerBundle\Service;

use Sententiaregum\Bundle\FollowerBundle\Entity\Api\AdvancedFollowerRepositoryInterface;
use Sententiaregum\Bundle\FollowerBundle\Service\Api\FollowerAdviceInterface;
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
            return $this->userRepository->createRandomUserList($length);
        }

        $result = [];
        foreach ($this->followerRepository->findFollowingByFollowingOfUser($userId, $length) as $userId) {
            $result[] = $this->userRepository->findById($userId);
        }

        return $result;
    }
}
