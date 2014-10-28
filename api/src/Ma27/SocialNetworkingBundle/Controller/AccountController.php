<?php
namespace Ma27\SocialNetworkingBundle\Controller;

use Ma27\SocialNetworkingBundle\Entity\User\Api\UserInterface;
use Ma27\SocialNetworkingBundle\Entity\User\Api\UserRepositoryInterface;
use Ma27\SocialNetworkingBundle\Util\Api\PasswordHasherInterface;
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
     * @param ValidatorInterface $validator
     * @param UserRepositoryInterface $userRepository
     * @param PasswordHasherInterface $passwordHasher
     */
    public function __construct(
        ValidatorInterface $validator,
        UserRepositoryInterface $userRepository,
        PasswordHasherInterface $passwordHasher
    ) {
        $this->validator = $validator;
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function createAction(Request $request)
    {
        $user = $this->userRepository->create(
            $request->attributes->get('username'),
            $this->passwordHasher->create(
                $request->attributes->get('password')
            ),
            $request->attributes->get('email'),
            $request->attributes->get('registrationDate')
        );

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

        $isNotUniqueName  = null !== $this->userRepository->findByName($user->getUsername());
        $isNotUniqueEmail = null !== $this->userRepository->findByEmail($user->getEmail());

        $messages = [];
        if ($isNotUniqueName) {
            $messages['username'] = ['Username already in use'];
        }
        if ($isNotUniqueEmail) {
            $messages['email'] = ['Email already in use'];
        }

        return $this->finishRegistration(array_merge_recursive($messages, $errors), $user);
    }

    /**
     * @param string[] $errors
     * @param UserInterface $user
     * @return JsonResponse
     */
    private function finishRegistration(array $errors, UserInterface $user)
    {
        if (count($errors) > 0) {
            return new JsonResponse(['errors' => $errors]);
        }

        $this->userRepository->add($user);
        return new JsonResponse(['username' => $user->getUsername()]);
    }
}
