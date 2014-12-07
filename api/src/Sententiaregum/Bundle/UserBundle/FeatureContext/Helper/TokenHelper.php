<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\UserBundle\FeatureContext\Helper;

use Prophecy\Prophet;
use Sententiaregum\Bundle\UserBundle\Controller\TokenController;
use Sententiaregum\Bundle\UserBundle\Entity\Api\UserRepositoryInterface;
use Sententiaregum\Bundle\UserBundle\Util\Api\PasswordHasherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TokenHelper
{
    private $tokenController;
    private $userRepo;
    private $hasher;
    private $mocker;

    public function __construct(
        TokenController $tokenController,
        UserRepositoryInterface $userRepository,
        PasswordHasherInterface $hasher)
    {
        $this->tokenController = $tokenController;
        $this->userRepo = $userRepository;
        $this->hasher = $hasher;
        $this->mocker = new Prophet();
    }

    /**
     * @param string[] $credentials
     * @return mixed
     */
    public function requestToken(array $credentials)
    {
        $request = $this->mocker->prophesize(Request::class);
        $request->getContent()->willReturn(json_encode($credentials));

        return $this->generateResult($this->tokenController->requestTokenAction($request->reveal()));
    }

    public function createDummyUser()
    {
        $user = $this->userRepo->create('Ma27', $this->hasher->create('foobar'), 'Ma27@example.org', new \DateTime());

        $this->userRepo->add($user);
        return $this->userRepo->findByName('Ma27');
    }

    public function createLockedDummyAccount()
    {
        $user = $this->userRepo->create(
            'locked',
            $this->hasher->create('123456'),
            'locked@example.org',
            new \DateTime(),
            new \DateTime(),
            true
        );

        $this->userRepo->add($user);
        return $this->userRepo->findByName('locked');
    }

    public function flush()
    {
        $this->userRepo->flush();
    }

    /**
     * @param JsonResponse $response
     * @return mixed
     */
    private function generateResult(JsonResponse $response)
    {
        return json_decode($response->getContent(), true);
    }
}
 