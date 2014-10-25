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

class AccountControllerSpec extends ObjectBehavior
{
    function it_returns_unauthorized_if_the_credentials_cannot_be_loaded(
        TokenInterface $token, UserProvider $provider, PasswordHasherInterface $hasher
    ) {
        $provider->loadUserByUsername('Ma27')->willReturn(null);
        $this->beConstructedWith($token, $provider, $hasher);

        $request = Request::create('/');
        $request->attributes->set('username', 'Ma27');
        $result = $this->requestTokenAction($request);

        $result->shouldBeAnInstanceOf(JsonResponse::class);
        $result->getStatusCode()->shouldBe(401);
        $result->getContent()->shouldHasCredentialsError();
    }

    function it_creates_valid_token(
        TokenInterface $token, UserProvider $provider, PasswordHasherInterface $hasher
    ) {
        $provider->loadUserByUsername('Ma27')->willReturn((new User())->setId(1)->setUsername('Ma27')->setPassword('123456'));
        $hasher->verify('123456', '123456')->willReturn(true);
        $token->generateToken()->willReturn($this->getApiKeyFixture());
        $token->storeToken($this->getApiKeyFixture(), 1)->shouldBeCalled();

        $this->beConstructedWith($token, $provider, $hasher);

        $request = Request::create('/');
        $request->attributes->set('username', 'Ma27');
        $request->attributes->set('password', '123456');
        $result = $this->requestTokenAction($request);

        $result->shouldBeAnInstanceOf(JsonResponse::class);
        $result->getStatusCode()->shouldBe(200);
        $result->getContent()->shouldHaveValidToken();
    }

    public function getMatchers()
    {
        return [
            'hasCredentialsError' => function ($subject) {
                $data = json_decode($subject, true);
                if (!isset($data['errors'])) {
                    return false;
                }

                return $data['errors'] === array('Invalid credentials');
            },
            'haveValidToken' => function ($subject) {
                $token = json_decode($subject, true)['token'];
                return strlen($token) === 255;
            }
        ];
    }

    private function getApiKeyFixture()
    {
        $str = (string) '';
        for ($i = 0; $i < 255; $i++) {
            $str .= '1';
        }

        return $str;
    }
}
