<?php

namespace Sententiaregum\Bundle\UserBundle\FeatureContext\Helper;

use PHPUnit_Framework_Assert as Test;
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

    public function __construct(
        UserRepositoryInterface $userRepositoryInterface,
        AccountController $controller)
    {
        $this->repository = $userRepositoryInterface;
        $this->accountController = $controller;
    }

    public function flush()
    {
        $this->repository->flush();
    }

    public function createAccount($username, $password, $email, $realName)
    {
        $request = Request::create('/');
        $request->attributes->set('username', $username);
        $request->attributes->set('password', $password);
        $request->attributes->set('email', $email);
        $request->attributes->set('realname', $realName);

        $response = $this->accountController->createAction($request);
        Test::assertInstanceOf(JsonResponse::class, $response);

        return json_decode($response->getContent(), true);
    }
}
 