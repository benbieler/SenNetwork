<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\FollowerBundle\Controller;

use Sententiaregum\Bundle\FollowerBundle\Entity\Api\FollowerRepositoryInterface;
use Sententiaregum\Bundle\FollowerBundle\Entity\Follower;
use Sententiaregum\Bundle\FollowerBundle\Service\Api\FollowerAdviceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class FollowerController
{
    /**
     * @var FollowerRepositoryInterface
     */
    private $followerRepository;

    /**
     * @var FollowerAdviceInterface
     */
    private $followerAdviceService;

    /**
     * @param FollowerRepositoryInterface $followerRepositoryInterface
     * @param FollowerAdviceInterface $followerAdviceInterface
     */
    public function __construct(
        FollowerRepositoryInterface $followerRepositoryInterface,
        FollowerAdviceInterface $followerAdviceInterface)
    {
        $this->followerRepository = $followerRepositoryInterface;
        $this->followerAdviceService = $followerAdviceInterface;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function indexAction(Request $request)
    {
        $input = json_decode($request->getContent(), true);
        /** @var integer $userId */
        $userId = \igorw\get_in($input, ['user_id']);
        if (null === $userId) {
            return new JsonResponse(['followers' => []]);
        }

        $followers = $this->followerRepository->findFollowingByUserId($userId);

        // convert follower entity list to simple list of following users
        /** @var Follower $value */
        array_walk($followers, function (&$value) {
            $following = $value->getFollowing();
            $value = $following;
        });

        return new JsonResponse(['followers' => $followers]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function createAction(Request $request)
    {
        $follower = $this->createFollowerEntity($request);
        $this->followerRepository->createRelation($follower);

        return new JsonResponse();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function removeAction(Request $request)
    {
        $follower = $this->createFollowerEntity($request);
        $this->followerRepository->dropRelation($follower);

        return new JsonResponse();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function generateFollowerAdviceAction(Request $request)
    {
        $input = json_decode($request->getContent(), true);

        /** @var integer $userId */
        $userId = \igorw\get_in($input, ['user_id']);
        $list = $this->followerAdviceService->createAdviceList($userId);

        array_walk($list, function (&$value) {  // transform result to readable format
            $name = $value->getUsername();
            $id = $value->getId();
            $value = ['username' => $name, 'id' => $id];
        });

        return new JsonResponse(['advices' => $list]);
    }

    /**
     * @param Request $request
     * @return Follower
     * @throws \InvalidArgumentException
     */
    private function createFollowerEntity(Request $request)
    {
        $input = json_decode($request->getContent(), true);

        /** @var integer $userId */
        $userId = \igorw\get_in($input, ['user_id']);
        /** @var integer $following */
        $following = \igorw\get_in($input, ['following_id']);

        if (null === $following || null === $userId) {
            throw new \InvalidArgumentException('User and Following id must not be null');
        }

        $follower = new Follower();
        $follower->setUser($userId);
        $follower->setFollowing($following);

        return $follower;
    }
}
