<?php

namespace spec\Ma27\SocialNetworkingBundle\Controller;

use Ma27\SocialNetworkingBundle\Entity\User\User;
use Ma27\SocialNetworkingBundle\Security\UserProvider;
use Ma27\SocialNetworkingBundle\Service\Api\TokenInterface;
use Ma27\SocialNetworkingBundle\Util\Api\PasswordHasherInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TokenControllerSpec extends ObjectBehavior
{
    function let(TokenInterface $token, UserProvider $userProvider, PasswordHasherInterface $hasher)
    {
        $this->beConstructedWith($token, $userProvider, $hasher);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Ma27\SocialNetworkingBundle\Controller\TokenController');
    }

    function it_validates_the_credentials_and_can_return_errors(UserProvider $userProvider)
    {
        $userProvider->loadUserByUsername('Ma27')->shouldBeCalled();

        $request = Request::create('/');
        $request->attributes->set('username', 'Ma27');
        $request->attributes->set('password', '123456');
        $result = $this->requestTokenAction($request);

        $result->shouldBeAnInstanceOf(JsonResponse::class);
        $result->shouldHasCredentialFailure();
    }

    function it_refuses_locked_accounts(UserProvider $userProvider, PasswordHasherInterface $hasher)
    {
        $userProvider->loadUserByUsername('Ma27')->willReturn($this->createLockedDummyUser());
        $hasher->verify('123456', '123456')->willReturn(true);

        $request = Request::create('/');
        $request->attributes->set('username', 'Ma27');
        $request->attributes->set('password', '123456');
        $result = $this->requestTokenAction($request);

        $result->shouldBeAnInstanceOf(JsonResponse::class);
        $result->shouldBeLocked();
    }

    function it_generates_an_api_token_for_verified_credentials(UserProvider $userProvider, PasswordHasherInterface $hasher, TokenInterface $token)
    {
        $userProvider->loadUserByUsername('Ma27')->willReturn($this->createDummyUser());
        $hasher->verify('123456', '123456')->willReturn(true);
        $token->storeToken(1)->willReturn(true);

        $request = Request::create('/');
        $request->attributes->set('username', 'Ma27');
        $request->attributes->set('password', '123456');
        $result = $this->requestTokenAction($request);

        $result->shouldBeAnInstanceOf(JsonResponse::class);
        $result->shouldContainApiToken();
    }

    public function getMatchers()
    {
        return [
            'hasCredentialFailure' => function ($result) {
                $content = $result->getContent();
                $subject = json_decode($content, true);

                if (!isset($subject['errors'])) {
                    return false;
                }

                return in_array('Invalid credentials', $subject['errors']);
            },
            'beLocked' => function ($result) {
                $content = $result->getContent();
                $subject = json_decode($content, true);

                if (!isset($subject['errors'])) {
                    return false;
                }

                return in_array('This account is locked', $subject['errors']);
            },
            'containApiToken' => function ($result) {
                $content = $result->getContent();
                $subject = json_decode($content, true);

                return array_key_exists('token', $subject);
            }
        ];
    }

    private function createLockedDummyUser()
    {
        return (new User())
            ->setId(1)
            ->setUsername('Ma27')
            ->setPassword('123456')
            ->setLocked(true);
    }

    private function createDummyUser()
    {
        return (new User())
            ->setId(1)
            ->setUsername('Ma27')
            ->setPassword('123456');
    }
}
