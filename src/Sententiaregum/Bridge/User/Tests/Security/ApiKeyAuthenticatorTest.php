<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bridge\User\Tests\Security;

use Sententiaregum\Bridge\User\Security\AdvancedUserProviderInterface;
use Sententiaregum\Bridge\User\Security\ApiKeyAuthenticator;
use Sententiaregum\CoreDomain\User\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class ApiKeyAuthenticatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getCredentialSet
     */
    public function testCreateToken($apiKey, $providerKey)
    {
        $request = Request::create('/');
        $request->headers->set(ApiKeyAuthenticator::API_KEY_HEADER, $apiKey);

        $apiKeyAuthenticator = new ApiKeyAuthenticator($this->getMock(AdvancedUserProviderInterface::class));
        /** @var PreAuthenticatedToken $token */
        $token               = $apiKeyAuthenticator->createToken($request, $providerKey);

        $this->assertInstanceOf(PreAuthenticatedToken::class, $token);
        $this->assertSame($apiKey, $token->getCredentials());
        $this->assertSame($providerKey, $token->getProviderKey());
    }

    /**
     * @dataProvider getCredentialSet
     * @expectedException \Symfony\Component\Security\Core\Exception\BadCredentialsException
     * @expectedExceptionMessage No ApiKey found in request!
     */
    public function testCreateTokenWithEmptyRequest($apiKey, $providerKey)
    {
        $request = Request::create('/');
        $apiKeyAuthenticator = new ApiKeyAuthenticator($this->getMock(AdvancedUserProviderInterface::class));
        $apiKeyAuthenticator->createToken($request, $providerKey);
    }

    public function testTokenSupport()
    {
        $apiKeyAuthenticator = new ApiKeyAuthenticator($this->getMock(AdvancedUserProviderInterface::class));

        $providerKey = 'provider';
        $token = $this->getMockBuilder(PreAuthenticatedToken::class)->disableOriginalConstructor()->getMock();
        $token
            ->expects($this->any())
            ->method('getProviderKey')
            ->will($this->returnValue($providerKey));

        $this->assertTrue($apiKeyAuthenticator->supportsToken($token, $providerKey));

        $this->assertFalse($apiKeyAuthenticator->supportsToken($token, 'foo'));
        $this->assertFalse($apiKeyAuthenticator->supportsToken($this->getMock(TokenInterface::class), $providerKey));
    }

    /**
     * @dataProvider getCredentialSet
     */
    public function testTokenAuthentication($apiKey, $providerKey)
    {
        $user = new User('username', 'password', 'email@example.org');

        $token = $this->getMock(TokenInterface::class);
        $token
            ->expects($this->any())
            ->method('getCredentials')
            ->will($this->returnValue($apiKey));

        $provider = $this->getMock(AdvancedUserProviderInterface::class);
        $provider
            ->expects($this->any())
            ->method('findUserByApiKey')
            ->will($this->returnValue($user));

        $authenticator = new ApiKeyAuthenticator($provider);
        /** @var PreAuthenticatedToken $token */
        $token         = $authenticator->authenticateToken($token, $this->getMock(UserProviderInterface::class), $providerKey);

        $this->assertInstanceOf(PreAuthenticatedToken::class, $token);

        $this->assertSame($user, $token->getUser());
        $this->assertSame($apiKey, $token->getCredentials());
        $this->assertSame($providerKey, $token->getProviderKey());
    }

    /**
     * @dataProvider getCredentialSet
     * @expectedException \Symfony\Component\Security\Core\Exception\AuthenticationException
     * @expectedExceptionMessageRegExp /^API key \w+ does not exist!$/
     */
    public function testInvalidTokenAUthentication($apiKey, $providerKey)
    {
        $token = $this->getMock(TokenInterface::class);
        $token
            ->expects($this->any())
            ->method('getCredentials')
            ->will($this->returnValue($apiKey));

        $provider = $this->getMock(AdvancedUserProviderInterface::class);
        $provider
            ->expects($this->any())
            ->method('findUserByApiKey')
            ->will($this->returnValue(false));

        $authenticator = new ApiKeyAuthenticator($provider);
        $authenticator->authenticateToken($token, $this->getMock(UserProviderInterface::class), $providerKey);
    }

    public function testFailureHandler()
    {
        $username = 'admin';

        $token = $this->getMock(TokenInterface::class);
        $token
            ->expects($this->any())
            ->method('getUsername')
            ->will($this->returnValue($username));

        $exception = new AuthenticationException();
        $exception->setToken($token);

        $authenticator = new ApiKeyAuthenticator($this->getMock(AdvancedUserProviderInterface::class));

        $response = $authenticator->onAuthenticationFailure(Request::create('/'), $exception);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame($response->getStatusCode(), Response::HTTP_UNAUTHORIZED);

        $content = json_decode($response->getContent(), true);
        $this->assertSame('Credentials refused!', $content['reason']);
        $this->assertSame($username, $content['username']);
    }

    public function getCredentialSet()
    {
        return [
            [
                uniqid(),
                'anon.'
            ]
        ];
    }
}
