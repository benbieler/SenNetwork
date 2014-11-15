<?php

namespace Sententiaregum\Bundle\UserBundle\Controller;

use Sententiaregum\Bundle\UserBundle\Entity\Api\UserRepositoryInterface;
use Sententiaregum\Bundle\UserBundle\Service\CreateAccountInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AccountController
{
    /**
     * @var string[]
     */
    private $defaultRoles;

    /**
     * @var CreateAccountInterface
     */
    private $createAccount;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    public function __construct(
        CreateAccountInterface $createAccountService,
        UserRepositoryInterface $userRepositoryInterface,
        array $defaultRoles
    ) {
        $this->userRepository = $userRepositoryInterface;
        $this->defaultRoles = $defaultRoles;
        $this->createAccount = $createAccountService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function createAction(Request $request)
    {
        $content = json_decode($request->getContent(), true);
        $user = $this->userRepository->create(
            \igorw\get_in($content, ['username']),
            \igorw\get_in($content, ['password']),
            \igorw\get_in($content, ['email']),
            new \DateTime()
        );
        $user->setRealName(\igorw\get_in($content, ['realname']));

        $errors = $this->createAccount->validateInput($user);
        if (count($errors) > 0) {
            return new JsonResponse(['errors' => $errors]);
        }

        $user->setRoles($this->defaultRoles);
        $this->createAccount->persist($user);

        return new JsonResponse(['username' => $user->getUsername()]);
    }
}
