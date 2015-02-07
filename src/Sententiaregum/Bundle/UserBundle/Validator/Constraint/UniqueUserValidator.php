<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\UserBundle\Validator\Constraint;

use Sententiaregum\Bundle\UserBundle\Exception\RuntimeException;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Validator which validates the uniqueness of an entity
 */
class UniqueUserValidator extends ConstraintValidator
{
    /**
     * @var RegistryInterface
     */
    private $registry;

    /**
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof UniqueUser) {
            throw new UnexpectedTypeException($constraint, UniqueUser::class);
        }

        if (!is_string($value)
            && !is_object($value) && !method_exists($constraint, '__toString')) {

            throw new UnexpectedTypeException($value, 'string');
        }

        $entityManager = $this->registry->getManager($constraint->em);
        if (!$entityManager) {
            throw new RuntimeException(sprintf('Invalid manager %s', $constraint->em));
        }

        $repository = $entityManager->getRepository($constraint->entity);

        $result = $repository->findOneBy([$constraint->property => $value]);
        if (null === $result || count($result) > 0) {
            $this->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
