<?php

namespace Sententiaregum\Bundle\UserBundle\Controller;

use Sententiaregum\Bundle\UserBundle\Entity\User;
use Sententiaregum\Bundle\UserBundle\Security\UserProvider;
use Sententiaregum\Bundle\UserBundle\Security\Api\TokenInterface;
use Sententiaregum\Bundle\UserBundle\Util\Api\PasswordHasherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TokenController
{
    /**
     * @var TokenInterface
     */
    private $tokenService;

    /**
     * @var UserProvider
     */
    private $userProvider;

    /**
     * @var PasswordHasherInterface
     */
    private $hasher;

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
        $data = json_decode($request->getContent(), true);
        $username = \igorw\get_in($data, ['username']);
        $password = \igorw\get_in($data, ['password']);

        /** @var User $user */
        $user = $this->userProvider->loadUserByUsername($username);
        if (null === $user) {
            return $this->createInvalidCredentialsResponse();
        }

        if (!$this->hasher->verify($password, $user->getPassword())) {
            return $this->createInvalidCredentialsResponse();
        }

        if (false === $user->isAccountNonLocked()) {
            return $this->createLockedAccountResponse();
        }

        $token = $this->userProvider->findApiKeyByUserId($user->getId());
        if (null !== $token) {
            $token = $this->tokenService->storeToken($user->getId());

            return new JsonResponse(['token' => $token]);
        }

        return new JsonResponse(['token' => $token]);
    }

    /**
     * @return JsonResponse
     */
    private function createInvalidCredentialsResponse()
    {
        return new JsonResponse(['errors' => ['Invalid credentials']], 401);
    }

    /**
     * @return JsonResponse
     */
    private function createLockedAccountResponse()
    {
        return new JsonResponse(['errors' => ['This account is locked']], 401);
    }
}
