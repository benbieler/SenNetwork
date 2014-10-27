<?php
namespace Ma27\SocialNetworkingBundle\Controller;

use Ma27\SocialNetworkingBundle\Entity\User\Api\UserRepositoryInterface;
use Ma27\SocialNetworkingBundle\Util\Api\PasswordHasherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationListInterface;
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
            $this->passwordHasher->create($request->attributes->get('password')),
            $request->attributes->get('email'),
            $request->attributes->get('registrationDate')
        );

        $violations = $this->validator->validate($user);
        if (count($violations) > 0) {
            $errors = $this->convertValidationErrorsToErrorList($violations);
        } else {
            $errors = array();
        }

        $isUniqueUser = null === $this->userRepository->findByName($user->getUsername());
        $isUniqueMail = null === $this->userRepository->findByEmail($user->getEmail());

        foreach (['username' => $isUniqueUser, 'email' => $isUniqueMail] as $property => $result) {
            $errors = $this->handleUniqueError($property, $result, $errors);
        }
        if (count($errors) > 0) {
            return new JsonResponse(['errors' => $errors]);
        }

        $this->userRepository->add($user);
        return new JsonResponse(['username' => $user->getUsername()]);
    }

    /**
     * @param ConstraintViolationListInterface $violations
     * @return string[]
     */
    private function convertValidationErrorsToErrorList(ConstraintViolationListInterface $violations)
    {
        $errors = [];

        /** @var \Symfony\Component\Validator\ConstraintViolation $constraint */
        foreach (iterator_to_array($violations) as $constraint) {
            $propertyPath = $constraint->getPropertyPath();

            if (!isset($errors[$propertyPath])) {
                $errors[$propertyPath] = array();
            }
            $errors[$propertyPath][] = $constraint->getMessage();
        }
        return $errors;
    }

    private function handleUniqueError($property, $result, array $errors)
    {
        if (!$result) {
            if (!isset($errors[$property])) {
                $errors[$property] = array();
            }

            $errors[$property][] = sprintf('Your desired %s is already in use', $property);
        }

        return $errors;
    }
}
