<?php

namespace spec\Ma27\SocialNetworkingBundle\Controller;

use Ma27\SocialNetworkingBundle\Controller\AccountController;
use Ma27\SocialNetworkingBundle\Entity\User\Api\UserRepositoryInterface;
use Ma27\SocialNetworkingBundle\Entity\User\User;
use Ma27\SocialNetworkingBundle\Security\UserProvider;
use Ma27\SocialNetworkingBundle\Service\Token;
use Ma27\SocialNetworkingBundle\Util\PasswordHasher;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TokenControllerSpec extends ObjectBehavior
{
    function it_returns_unauthorized_if_the_credentials_cannot_be_loaded(UserRepositoryInterface $userRepository, Token $token)
    {
        $hasher = new PasswordHasher();
        $userRepository->findByName('undefined')->shouldBeCalled();
        $this->beConstructedWith($token, new UserProvider($userRepository->getWrappedObject()), $hasher);

        $request = Request::create('/');
        $request->attributes->set('username', 'undefined');
        $result = $this->requestTokenAction($request);

        $result->shouldBeAnInstanceOf(JsonResponse::class);
        $result->getStatusCode()->shouldBe(401);
        $result->getContent()->shouldHasCredentialsError();
    }

    function it_creates_and_returns_valid_token(UserRepositoryInterface $userRepository, Token $token)
    {
        $hasher = new PasswordHasher();

        $userRepository->findByName('Ma27')->willReturn($this->getMockUser());

        $token->storeToken(Argument::any(), Argument::any())->willReturn(true);
        $token->generateToken()->willReturn($this->getRandomToken());

        $userProvider = new UserProvider($userRepository->getWrappedObject());

        $this->beConstructedWith($token, $userProvider, $hasher);

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

    private function getMockUser()
    {
        return (new User())
            ->setId(1)
            ->setUsername('Ma27')
            ->setPassword((new PasswordHasher())->create('123456'));
    }

    private function getRandomToken()
    {
        $pool = 'abcdefghijklmnopqrstuvwxyz123456789';
        $string = (string) '';
        $split = str_split($pool);
        $availableChars = count($split) - 1;

        for ($i = 0; $i < 255; $i++) {
            $string .= $split[mt_rand(0, $availableChars)];
        }

        return $string;
    }
}
