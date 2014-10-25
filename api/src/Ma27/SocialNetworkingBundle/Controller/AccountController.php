<?php

namespace Ma27\SocialNetworkingBundle\Controller;

use Ma27\SocialNetworkingBundle\Security\UserProvider;
use Ma27\SocialNetworkingBundle\Service\Api\TokenInterface;
use Ma27\SocialNetworkingBundle\Util\Api\PasswordHasherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AccountController
{
    /**
     * @var TokenInterface
     */
    protected $tokenService;

    /**
     * @var UserProvider
     */
    protected $userProvider;

    /**
     * @var PasswordHasherInterface
     */
    protected $hasher;

    /**
     * @param TokenInterface $token
     * @param UserProvider $provider
     * @param PasswordHasherInterface $hasher
     */
    public function __construct(TokenInterface $token, UserProvider $provider, PasswordHasherInterface $hasher)
    {
        $this->tokenService = $token;
        $this->userProvider = $provider;
        $this->hasher = $hasher;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function requestTokenAction(Request $request)
    {
        $username = $this->userProvider->loadUserByUsername($request->attributes->get('username'));
        if (null === $username) {
            return $this->createInvalidCredentialsException();
        }

        if (!$this->hasher->verify($request->attributes->get('password'), $username->getPassword())) {
            return $this->createInvalidCredentialsException();
        }

        if (false === $username->isAccountNonLocked()) {
            return $this->createLockedAccountException();
        }

        $apiToken = $this->tokenService->generateToken();
        $this->tokenService->storeToken($apiToken, $username->getId());

        return new JsonResponse(['token' => $apiToken]);
    }

    /**
     * @return JsonResponse
     */
    private function createInvalidCredentialsException()
    {
        return new JsonResponse(['errors' => ['Invalid credentials']], 401);
    }

    /**
     * @return JsonResponse
     */
    private function createLockedAccountException()
    {
        return new JsonResponse(['errors' => ['This account is locked']], 401);
    }
}
