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

use Sententiaregum\Bundle\UserBundle\Exception\RuntimeException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\SimplePreAuthenticatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

/**
 * Concrete implementation of an authentication with an api key
 */
class ApiKeyAuthenticator implements SimplePreAuthenticatorInterface, AuthenticationFailureHandlerInterface
{
    /**
     * @var string
     */
    const API_KEY_HEADER = 'X-SEN-USER-TOKEN';

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = [
            'reason'   => 'Credentials refused!',
            'username' => !$exception->getToken() ? 'unknown' : $exception->getToken()->getUsername()
        ];

        return new JsonResponse($data, JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * Returns an authenticated token
     *
     * @param TokenInterface $token
     * @param UserProviderInterface $userProvider
     * @param string $providerKey
     *
     * @return PreAuthenticatedToken
     *
     * @throws AuthenticationException If the api key does not exist or is invalid
     * @throws RuntimeException If $userProvider is not an instance of AdvancedUserProviderInterface
     */
    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        if (!$userProvider instanceof AdvancedUserProviderInterface) {
            throw new RuntimeException(
                sprintf('The api key provider must implement %s', AdvancedUserProviderInterface::class)
            );
        }

        $apiKey = $token->getCredentials();
        $user   = $userProvider->findUserByApiKey($apiKey);

        if (!$user) {
            throw new AuthenticationException(
                sprintf('API key %s does not exist!', $apiKey)
            );
        }

        return new PreAuthenticatedToken(
            $user,
            $apiKey,
            $providerKey,
            $user->getRoles()
        );
    }

    /**
     * Checks if the token is supported
     *
     * @param TokenInterface $token
     * @param string $providerKey
     *
     * @return boolean
     */
    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken && $providerKey === $token->getProviderKey();
    }

    /**
     * Creates an api key by the http request
     *
     * @param Request $request
     * @param string $providerKey
     *
     * @return PreAuthenticatedToken
     *
     * @throws BadCredentialsException If the request token cannot be found
     */
    public function createToken(Request $request, $providerKey)
    {
        $apiKey = $request->headers->get(static::API_KEY_HEADER);

        if (!$apiKey) {
            throw new BadCredentialsException('No ApiKey found in request!');
        }

        return new PreAuthenticatedToken(
            'anon.',
            $apiKey,
            $providerKey
        );
    }
}
