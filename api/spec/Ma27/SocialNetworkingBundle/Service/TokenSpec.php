<?php

namespace spec\Ma27\SocialNetworkingBundle\Service;

use Ma27\SocialNetworkingBundle\Entity\User\Api\UserRepositoryInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TokenSpec extends ObjectBehavior
{
    function it_is_initializable(UserRepositoryInterface $userRepository)
    {
        $this->beConstructedWith($userRepository);
        $this->shouldHaveType('Ma27\SocialNetworkingBundle\Service\Token');
    }

    function it_generates_an_secure_token(
        UserRepositoryInterface $userRepositoryInterface
    ) {
        $this->beConstructedWith($userRepositoryInterface);
        $this->generateToken()->shouldBeString();
    }

    function it_does_not_store_token_in_case_of_repository_failure(
        UserRepositoryInterface $userRepositoryInterface
    ) {
        $userRepositoryInterface->storeToken('s3cr3t token', 1)->willReturn(false);

        $this->beConstructedWith($userRepositoryInterface);
        $this->shouldThrow(\OverflowException::class)->duringStoreToken('s3cr3t token', 1);
    }

    function it_stores_token_in_the_repository(
        UserRepositoryInterface $userRepositoryInterface
    ) {
        $userRepositoryInterface->storeToken('s3cr3t token', 1)->willReturn(true);

        $this->beConstructedWith($userRepositoryInterface);
        $this->storeToken('s3cr3t token', 1)->shouldBe(true);
    }
}
