<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace spec\Sententiaregum\Bundle\UserBundle\Security;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sententiaregum\Bundle\UserBundle\Entity\User;
use Sententiaregum\Bundle\UserBundle\Security\UserProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

class ApiKeyAuthenticationSpec extends ObjectBehavior
{
    function let(UserProvider $userProvider)
    {
        $this->beConstructedWith($userProvider);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sententiaregum\Bundle\UserBundle\Security\ApiKeyAuthentication');
    }

    function it_verifies_token_support(UserInterface $userInterface)
    {
        $token = new PreAuthenticatedToken($userInterface->getWrappedObject(), 'any', 'PHPSpec', []);

        $this->supportsToken($token, 'PHPSpec')->shouldBe(true);
    }

    function it_generates_an_server_response_for_auth_failures()
    {
        $this->shouldImplement(AuthenticationFailureHandlerInterface::class);
        $response = $this->onAuthenticationFailure(Request::create('/'), new AuthenticationException());

        $response->getStatusCode()->shouldBe(401);
    }

    function it_generates_token_model_by_the_http_request()
    {
        $request = Request::create('/');
        $keyFixture = 'request token';

        $request->headers->set('X-SEN-USER-TOKEN', $keyFixture);
        $result = $this->createToken($request, 'PHPSpec');

        $result->shouldBeAnInstanceOf(PreAuthenticatedToken::class);
        $result->getCredentials()->shouldBe($keyFixture);
    }

    function it_throws_exception_if_no_token_is_present_in_the_request()
    {
        $this->shouldThrow(new BadCredentialsException('Cannot find API key in the request headers'))->duringCreateToken(Request::create('/'), 'PHPSpec');
    }

    function it_authenticates_the_api_token(UserProvider $userProvider)
    {
        $user = new User();
        $userProvider->findUserByApiToken('any token')->willReturn($user);

        $token = new PreAuthenticatedToken('unknown', 'any token', 'PHPSpec');
        $result = $this->authenticateToken($token, $userProvider, 'PHPSpec');

        $result->shouldBeAnInstanceOf(PreAuthenticatedToken::class);
        $result->getUser()->shouldBe($user);
    }
}
