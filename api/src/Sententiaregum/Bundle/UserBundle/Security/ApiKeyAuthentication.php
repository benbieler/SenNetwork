<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\UserBundle\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\SimplePreAuthenticatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

class ApiKeyAuthentication implements SimplePreAuthenticatorInterface, AuthenticationFailureHandlerInterface
{
    /**
     * @var UserProvider
     */
    private $userProvider;

    /**
     * @param UserProvider $userProvider
     */
    public function __construct(UserProvider $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    /**
     * @return UserProvider
     */
    protected function getUserProvider()
    {
        return $this->userProvider;
    }

    /**
     * @param TokenInterface $token
     * @param UserProviderInterface $userProvider
     * @param string $providerKey
     * @return PreAuthenticatedToken
     * @throws AuthenticationException
     */
    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        $apiKey = $token->getCredentials();
        $user = $this->userProvider->findUserByApiToken($apiKey);

        if (!$user) {
            throw new AuthenticationException(sprintf('Cannot find api key %s', $apiKey));
        }

        return new PreAuthenticatedToken(
            $user,
            $apiKey,
            $providerKey,
            $user->getRoles() ?: []
        );
    }

    /**
     * @param TokenInterface $token
     * @param mixed $providerKey
     * @return boolean
     */
    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
    }

    /**
     * @param Request $request
     * @param string $providerKey
     * @return PreAuthenticatedToken
     * @throws BadCredentialsException
     */
    public function createToken(Request $request, $providerKey)
    {
        $key = $request->headers->get('X-SEN-USER-TOKEN');
        if (!$key) {
            throw new BadCredentialsException('Cannot find API key in the request headers');
        }

        return new PreAuthenticatedToken('unknown', $key, $providerKey);
    }

    /**
     * @param Request $request
     * @param AuthenticationException $exception
     * @return JsonResponse
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse(
            [
                'username' => null === $exception->getToken() ? 'unknown' : $exception->getToken()->getUsername(),
                'code' => 401,
                'reason' => 'unauthorized'
            ],
            401
        );
    }
}
