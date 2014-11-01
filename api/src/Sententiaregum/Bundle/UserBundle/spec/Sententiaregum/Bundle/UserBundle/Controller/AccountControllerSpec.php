<?php

namespace spec\Sententiaregum\Bundle\UserBundle\Controller;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sententiaregum\Bundle\UserBundle\Entity\Api\UserRepositoryInterface;
use Sententiaregum\Bundle\UserBundle\Entity\User;
use Sententiaregum\Bundle\UserBundle\Util\Api\PasswordHasherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AccountControllerSpec extends ObjectBehavior
{
    function let(
        ValidatorInterface $validator,
        UserRepositoryInterface $userRepositoryInterface,
        PasswordHasherInterface $passwordHasherInterface)
    {
        $passwordHasherInterface->create(Argument::any())->willReturnArgument(0);
        $this->beConstructedWith($validator, $userRepositoryInterface, $passwordHasherInterface);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sententiaregum\Bundle\UserBundle\Controller\AccountController');
    }

    function it_validates_account_input(
        ValidatorInterface $validator,
        UserRepositoryInterface $userRepositoryInterface)
    {
        // setup fixtures
        $violationFixture = new ConstraintViolationList();
        $violation = new ConstraintViolation('Username is invalid', null, [], null, 'username', null);
        $violationFixture->add($violation);

        $validator->validate(Argument::any())->willReturn($violationFixture);

        $userRepositoryInterface->findByName(Argument::any())->shouldBeCalled();
        $userRepositoryInterface->findByEmail(Argument::any())->shouldBeCalled();
        $userRepositoryInterface
            ->create(Argument::any(), Argument::any(), Argument::any(), Argument::any())
            ->willReturn((new User())->setUsername('Ma27')->setPassword('123456')->setEmail('Ma27@example.org'));

        // example code
        $request = Request::create('/');
        $request->attributes->set('username', 'Ma27');
        $request->attributes->set('password', '123456');
        $request->attributes->set('email', 'Ma27@example.org');

        $response = $this->createAction($request);
        $response->shouldBeAnInstanceOf(JsonResponse::class);

        $response->shouldHaveValidationErrors('username', 'Username is invalid');
    }

    function it_validates_unique_username_and_email(
        ValidatorInterface $validator,
        UserRepositoryInterface $userRepositoryInterface)
    {
        $validator->validate(Argument::any())->willReturn(new ConstraintViolationList());
        $userRepositoryInterface
            ->create(Argument::any(), Argument::any(), Argument::any(), Argument::any())
            ->willReturn((new User())->setUsername('Ma27')->setPassword('123456')->setEmail('Ma27@example.org'));

        $userRepositoryInterface->findByName('Ma27')->willReturn((new User())->setUsername('Ma27'));
        $userRepositoryInterface->findByEmail(Argument::any())->shouldBeCalled();

        // example
        $request = Request::create('/');
        $request->attributes->set('username', 'Ma27');
        $request->attributes->set('password', '123456');
        $request->attributes->set('email', 'Ma27@example.org');

        $response = $this->createAction($request);
        $response->shouldBeAnInstanceOf(JsonResponse::class);

        $response->shouldHaveValidationErrors('username', 'Username already in use');
    }

    function it_stores_accounts_with_valid_credentials(
        ValidatorInterface $validator,
        UserRepositoryInterface $userRepositoryInterface)
    {
        $validator->validate(Argument::any())->willReturn(new ConstraintViolationList());
        $userRepositoryInterface
            ->create(Argument::any(), Argument::any(), Argument::any(), Argument::any())
            ->willReturn((new User())->setUsername('Ma27')->setPassword('123456')->setEmail('Ma27@example.org'));

        $userRepositoryInterface->findByName('Ma27')->shouldBeCalled();
        $userRepositoryInterface->findByEmail(Argument::any())->shouldBeCalled();
        $userRepositoryInterface->add(Argument::any())->shouldBeCalled();

        // example
        $request = Request::create('/');
        $request->attributes->set('username', 'Ma27');
        $request->attributes->set('password', '123456');
        $request->attributes->set('email', 'Ma27@example.org');

        $response = $this->createAction($request);
        $response->shouldBeAnInstanceOf(JsonResponse::class);

        $response->shouldContainUserName('Ma27');
    }

    public function getMatchers()
    {
        return [
            'haveValidationErrors' => function ($response, $property, $message) {
                $content = $response->getContent();
                $data = json_decode($content, true);

                return isset($data['errors']) && isset($data['errors'][$property]) && in_array($message, $data['errors'][$property]);
            },
            'containUserName' => function ($response, $name) {
                $content = $response->getContent();
                $data = json_decode($content, true);

                return isset($data['username']) && $data['username'] === $name;
            }
        ];
    }
}
