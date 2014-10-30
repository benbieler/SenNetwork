<?php

namespace spec\Ma27\SocialNetworkingBundle\Security;

use Ma27\SocialNetworkingBundle\Security\UserProvider;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ApiKeyAuthenticationSpec extends ObjectBehavior
{
    private $providerKey;

    function let(UserProvider $userProvider)
    {
        $this->providerKey = 'PHPSPec';
        $this->beConstructedWith($userProvider);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Ma27\SocialNetworkingBundle\Security\ApiKeyAuthentication');
    }
}
