<?php

namespace spec\Sententiaregum\Bundle\UserBundle\Service;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sententiaregum\Bundle\UserBundle\Entity\Api\UserRepositoryInterface;
use Sententiaregum\Bundle\UserBundle\Entity\User;
use Sententiaregum\Bundle\UserBundle\Util\Api\PasswordHasherInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateAccountSpec extends ObjectBehavior
{
    function let(PasswordHasherInterface $passwordHasherInterface, UserRepositoryInterface $userRepositoryInterface, ValidatorInterface $validatorInterface)
    {
        $this->beConstructedWith($passwordHasherInterface, $userRepositoryInterface, $validatorInterface);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sententiaregum\Bundle\UserBundle\Service\CreateAccount');
    }

    function it_validates_the_user_input(
        ValidatorInterface $validatorInterface)
    {
        $validatorInterface->validate(Argument::any())->willReturn(new ConstraintViolationList());

        $user = new User();
        $this->validateInput($user)->shouldHaveCount(0);
    }
}
