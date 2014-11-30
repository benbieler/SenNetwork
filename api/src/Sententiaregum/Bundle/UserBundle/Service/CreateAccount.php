<?php

namespace Sententiaregum\Bundle\UserBundle\Service;

use Sententiaregum\Bundle\UserBundle\Entity\Api\UserInterface;
use Sententiaregum\Bundle\UserBundle\Entity\Api\UserRepositoryInterface;
use Sententiaregum\Bundle\UserBundle\Util\Api\PasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateAccount implements CreateAccountInterface
{
    /**
     * @var PasswordHasherInterface
     */
    private $passwordHasher;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @param PasswordHasherInterface $passwordHasher
     * @param UserRepositoryInterface $userRepository
     * @param ValidatorInterface $validator
     */
    public function __construct(
        PasswordHasherInterface $passwordHasher, UserRepositoryInterface $userRepository, ValidatorInterface $validator
    ) {
        $this->passwordHasher = $passwordHasher;
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }

    /**
     * @param UserInterface $user
     * @return void
     */
    public function persist(UserInterface $user)
    {
        $user->setPassword($this->passwordHasher->create($user->getPassword()));
        $this->userRepository->add($user);
        $storedUser = $this->userRepository->findByName($user->getUsername());
        $this->userRepository->attachRolesOnUser($user->getRoles(), $storedUser->getId());
    }

    /**
     * @param UserInterface $user
     * @return string[]
     */
    public function validateInput(UserInterface $user)
    {
        $violations = [];
        /** @var \Symfony\Component\Validator\ConstraintViolationInterface $constraintViolation */
        foreach ($this->validator->validate($user) as $constraintViolation) {
            if (!isset($violations[$constraintViolation->getPropertyPath()])) {
                $violations[$constraintViolation->getPropertyPath()] = [];
            }

            $violations[$constraintViolation->getPropertyPath()][] = $constraintViolation->getMessage();
        }

        return $violations;
    }
}
