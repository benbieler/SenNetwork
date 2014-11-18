<?php

namespace spec\Sententiaregum\Bundle\MicrobloggingBundle\Controller;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sententiaregum\Bundle\MicrobloggingBundle\Entity\Api\MicroblogRepositoryInterface;
use Sententiaregum\Bundle\MicrobloggingBundle\Entity\MicroblogEntry;
use Sententiaregum\Bundle\RedisMQBundle\Api\QueueInputInterface;
use Sententiaregum\Bundle\UserBundle\Entity\Api\UserInterface;
use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class WriteEntryControllerSpec extends ObjectBehavior
{
    function let(
        SecurityContextInterface $securityContextInterface,
        MicroblogRepositoryInterface $microblogRepositoryInterface,
        QueueInputInterface $queueInputInterface,
        ValidatorInterface $validatorInterface)
    {
        $this->beConstructedWith($securityContextInterface, $microblogRepositoryInterface, $queueInputInterface, $validatorInterface);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sententiaregum\Bundle\MicrobloggingBundle\Controller\WriteEntryController');
    }

    function it_validates_the_user_input(
        ValidatorInterface $validatorInterface,
        Request $request,
        UserInterface $userInterface,
        SecurityContextInterface $securityContextInterface, TokenInterface $tokenInterface, FileBag $fileBag)
    {
        $tokenInterface->getUser()->willReturn($userInterface);
        $securityContextInterface->getToken()->willReturn($tokenInterface);

        $request->getContent()->willReturn(json_encode(['content' => null]));

        // just a little hack because prophecy cannot mock properties
        $request->files = $fileBag;

        $violations = new ConstraintViolationList([new ConstraintViolation('error message', null, [], null, null, null)]);
        $validatorInterface->validate(Argument::any())->willReturn($violations);

        $result = $this->createAction($request);

        $result->shouldBeAnInstanceOf(JsonResponse::class);
        $result->shouldContainErrors(['error message']);
    }

    function it_persists_the_posts(
        ValidatorInterface $validatorInterface,
        TokenInterface $tokenInterface,
        UserInterface $userInterface,
        SecurityContextInterface $securityContextInterface,
        Request $request,
        FileBag $fileBag,
        MicroblogRepositoryInterface $microblogRepositoryInterface)
    {
        $tokenInterface->getUser()->willReturn($userInterface);
        $securityContextInterface->getToken()->willReturn($tokenInterface);

        $request->getContent()->willReturn(json_encode(['content' => $content = 'hello world']));

        $microblogRepositoryInterface->create(Argument::any(), Argument::any(), Argument::any(), Argument::any())->willReturn((new MicroblogEntry())->setContent($content));
        $microblogRepositoryInterface->add(Argument::any())->shouldBeCalled();

        $validatorInterface->validate(Argument::any())->willReturn(new ConstraintViolationList());

        // just a little hack because prophecy cannot mock properties
        $request->files = $fileBag;

        $result = $this->createAction($request);
        $result->shouldHaveType(JsonResponse::class);

        $result->shouldHasPost($content);
    }

    public function getMatchers()
    {
        return [
            'containErrors' => function ($subject, array $errors) {
                $data = json_decode($subject->getContent(), true);
                return $data['errors'] === $errors;
            },
            'hasPost' => function ($subject, $content) {
                $data = json_decode($subject->getContent(), true);
                return $data['content'] === $content;
            }
        ];
    }
}
