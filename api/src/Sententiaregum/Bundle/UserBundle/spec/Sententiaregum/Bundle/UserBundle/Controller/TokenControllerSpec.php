<?php

namespace spec\Sententiaregum\Bundle\UserBundle\Controller;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sententiaregum\Bundle\UserBundle\Entity\User;
use Sententiaregum\Bundle\UserBundle\Security\UserProvider;
use Sententiaregum\Bundle\UserBundle\Security\Api\TokenInterface;
use Sententiaregum\Bundle\UserBundle\Util\Api\PasswordHasherInterface;
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
        $this->shouldHaveType('Sententiaregum\Bundle\UserBundle\Controller\TokenController');
    }

    function it_validates_the_credentials_and_can_return_errors(UserProvider $userProvider, Request $request)
    {
        $userProvider->loadUserByUsername('Ma27')->shouldBeCalled();

        $request = $this->mockCredentialRequest($request, ['username' => 'Ma27']);
        $result = $this->requestTokenAction($request);

        $result->shouldBeAnInstanceOf(JsonResponse::class);
        $result->shouldHasCredentialFailure();
    }

    function it_refuses_locked_accounts(UserProvider $userProvider, PasswordHasherInterface $hasher, Request $request)
    {
        $userProvider->loadUserByUsername('Ma27')->willReturn($this->createLockedDummyUser());
        $hasher->verify('123456', '123456')->willReturn(true);

        $request = $this->mockCredentialRequest($request, ['username' => 'Ma27', 'password' => '123456']);
        $result = $this->requestTokenAction($request);

        $result->shouldBeAnInstanceOf(JsonResponse::class);
        $result->shouldBeLocked();
    }

    function it_generates_an_api_token_for_verified_credentials(UserProvider $userProvider, PasswordHasherInterface $hasher, TokenInterface $token, Request $request)
    {
        $userProvider->loadUserByUsername('Ma27')->willReturn($this->createDummyUser());
        $hasher->verify('123456', '123456')->willReturn(true);
        $token->storeToken(1)->willReturn(true);

        $userProvider->findApiKeyByUserId(Argument::any())->shouldBeCalled();

        $request = $this->mockCredentialRequest($request, ['username' => 'Ma27', 'password' => '123456']);
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

    public function mockCredentialRequest($dummy, array $credentials)
    {
        $dummy->getContent()->willReturn(json_encode($credentials));
        return $dummy;
    }
}
