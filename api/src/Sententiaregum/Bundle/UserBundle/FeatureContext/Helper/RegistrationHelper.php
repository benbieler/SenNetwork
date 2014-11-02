<?php

namespace Sententiaregum\Bundle\UserBundle\FeatureContext\Helper;

use PHPUnit_Framework_Assert as Test;
use Prophecy\Prophet;
use Sententiaregum\Bundle\UserBundle\Controller\AccountController;
use Sententiaregum\Bundle\UserBundle\Entity\Api\UserRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RegistrationHelper
{
    /**
     * @var UserRepositoryInterface
     */
    private $repository;

    /**
     * @var AccountController
     */
    private $accountController;

    /**
     * @var Prophet
     */
    private $mocker;

    public function __construct(
        UserRepositoryInterface $userRepositoryInterface,
        AccountController $controller)
    {
        $this->repository = $userRepositoryInterface;
        $this->accountController = $controller;
        $this->mocker = new Prophet();
    }

    public function flush()
    {
        $this->repository->flush();
    }

    public function createAccount($username, $password, $email, $realName)
    {
        $request = $this->mocker->prophesize(Request::class);
        $request->getContent()->willReturn(json_encode([
            'username' => $username,
            'password' => $password,
            'email' => $email,
            'realname' => $realName
        ]));

        $response = $this->accountController->createAction($request->reveal());
        Test::assertInstanceOf(JsonResponse::class, $response);

        return json_decode($response->getContent(), true);
    }
}
 