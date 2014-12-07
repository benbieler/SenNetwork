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

use Sententiaregum\Bundle\UserBundle\Entity\Api\UserRepositoryInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TokenSpec extends ObjectBehavior
{
    function let(UserRepositoryInterface $userRepository)
    {
        $this->beConstructedWith($userRepository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sententiaregum\Bundle\UserBundle\Security\Token');
    }

    function it_generates_an_secure_api_token()
    {
        $this->generateToken()->shouldBeString();
    }

    function it_throws_an_exception_if_the_token_cannot_be_stored(UserRepositoryInterface $userRepo)
    {
        $userRepo->storeToken(Argument::any(), 1)->willReturn(false);

        $this->shouldThrow(new \OverflowException('Too many loops!'))->duringStoreToken(1);
    }

    function it_stores_to_the_token_in_the_database(UserRepositoryInterface $userRepository)
    {
        $userRepository->storeToken(Argument::any(), 1)->willReturn(true);

        $this->storeToken(1)->shouldBeString();
    }
}
