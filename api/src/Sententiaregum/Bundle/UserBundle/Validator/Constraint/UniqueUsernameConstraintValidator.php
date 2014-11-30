<?php

namespace Sententiaregum\Bundle\UserBundle\Validator\Constraint;

use Sententiaregum\Bundle\UserBundle\Entity\Api\UserRepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueUsernameConstraintValidator extends ConstraintValidator
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     *
     * @throws UnexpectedTypeException
     *
     * @api
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof UniqueUsernameConstraint) {
            throw new UnexpectedTypeException($constraint, UniqueUsernameConstraint::class);
        }

        if (
            !is_string($value)
            || !is_object($value) && method_exists($value, '__toString')
        ) {
            throw new UnexpectedTypeException($value, 'string');
        }

        if (null !== $this->userRepository->findByName($value)) {
            $this->buildViolation($constraint->message, ['{{ name }}' => $value])
                ->addViolation();
        }
    }
}
