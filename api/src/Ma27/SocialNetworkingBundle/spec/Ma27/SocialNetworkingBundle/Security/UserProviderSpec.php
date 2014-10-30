<?php

namespace spec\Ma27\SocialNetworkingBundle\Security;

use Ma27\SocialNetworkingBundle\Entity\User\Api\UserRepositoryInterface;
use Ma27\SocialNetworkingBundle\Entity\User\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UserProviderSpec extends ObjectBehavior
{
    function let(UserRepositoryInterface $userRepo)
    {
        $this->beConstructedWith($userRepo);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Ma27\SocialNetworkingBundle\Security\UserProvider');
    }

    function it_verifies_the_given_model()
    {
        $user = new User();
        $this->supportsClass($user)->shouldBe(true);
    }

    function it_can_fetch_the_user_by_the_api_token(UserRepositoryInterface $userRepo)
    {
        $token = hash('sha1', uniqid());
        $userRepo->findUserIdByApiToken($token)->willReturn(1);

        $user = new User();
        $userRepo->findById(1)->willReturn($user);

        $this->findUserByApiToken($token)->shouldBe($user);
    }
}
