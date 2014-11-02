<?php

namespace Sententiaregum\Bundle\UserBundle\Controller;

use Sententiaregum\Bundle\UserBundle\Entity\Api\UserRepositoryInterface;
use Sententiaregum\Bundle\UserBundle\Util\Api\PasswordHasherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AccountController
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var PasswordHasherInterface
     */
    private $passwordHasher;

    /**
     * @var string[]
     */
    private $defaultRoles;

    /**
     * @param ValidatorInterface $validator
     * @param UserRepositoryInterface $userRepository
     * @param PasswordHasherInterface $passwordHasher
     * @param string[] $defaultRoles
     */
    public function __construct(
        ValidatorInterface $validator,
        UserRepositoryInterface $userRepository,
        PasswordHasherInterface $passwordHasher,
        array $defaultRoles
    ) {
        $this->validator = $validator;
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
        $this->defaultRoles = $defaultRoles;
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

        $errors = [];
        $violations = $this->validator->validate($user);
        if (count($violations) > 0) {
            /** @var \Symfony\Component\Validator\ConstraintViolation $constraint */
            foreach (iterator_to_array($violations) as $constraint) {
                $propertyPath = $constraint->getPropertyPath();

                if (!isset($errors[$propertyPath])) {
                    $errors[$propertyPath] = array();
                }
                $errors[$propertyPath][] = $constraint->getMessage();
            }
        }
        $user->setPassword($this->passwordHasher->create($user->getPassword()));

        $isNotUniqueName  = null !== $this->userRepository->findByName($user->getUsername());
        $isNotUniqueEmail = null !== $this->userRepository->findByEmail($user->getEmail());

        $messages = [];
        if ($isNotUniqueName) {
            $messages['username'] = ['Username already in use'];
        }
        if ($isNotUniqueEmail) {
            $messages['email'] = ['Email already in use'];
        }

        $errors = array_merge_recursive($messages, $errors);
        if (count($errors) > 0) {
            return new JsonResponse(['errors' => $errors]);
        }

        $this->userRepository->add($user);

        $completeUser = $this->userRepository->findByName($user->getUsername());
        if (count($this->defaultRoles) > 0) {
            $this->userRepository->attachRolesOnUser($this->defaultRoles, $completeUser->getId());
        }

        return new JsonResponse(['username' => $user->getUsername()]);
    }
}
