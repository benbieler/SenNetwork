<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\CommonBundle\Test\Stub;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\ExecutionContextInterface;
use Symfony\Component\Validator\MetadataFactoryInterface;
use Symfony\Component\Validator\MetadataInterface;

class ConstraintContextFixture implements ExecutionContextInterface
{
    private $violations = [];

    /**
     * @param string $message
     * @param array $params
     * @param null $invalidValue
     * @param null $plural
     * @param null $code
     */
    public function addViolation($message, array $params = array(), $invalidValue = null, $plural = null, $code = null)
    {
        $this->violations[] = new ConstraintViolation($message, $message, $params, null, null, $invalidValue);
    }

    /**
     * @param string $subPath
     * @param string $message
     * @param array $parameters
     * @param null $invalidValue
     * @param null $plural
     * @param null $code
     */
    public function addViolationAt(
        $subPath,
        $message,
        array $parameters = array(),
        $invalidValue = null,
        $plural = null,
        $code = null
    ) {
        # TODO: Implement addViolationAt() method.
    }

    /**
     * @param mixed $value
     * @param string $subPath
     * @param null $groups
     * @param bool $traverse
     * @param bool $deep
     */
    public function validate($value, $subPath = '', $groups = null, $traverse = false, $deep = false)
    {
    }

    /**
     * @param mixed $value
     * @param Constraint|\Symfony\Component\Validator\Constraint[] $constraints
     * @param string $subPath
     * @param null $groups
     */
    public function validateValue($value, $constraints, $subPath = '', $groups = null)
    {
    }

    /**
     * @return ConstraintViolationListInterface
     */
    public function getViolations()
    {
        return new ConstraintViolationList($this->violations);
    }

    /**
     * @return mixed
     */
    public function getRoot()
    {
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
    }

    /**
     * @return null|MetadataInterface
     */
    public function getMetadata()
    {
    }

    /**
     * @return MetadataFactoryInterface
     */
    public function getMetadataFactory()
    {
    }

    /**
     * @return string
     */
    public function getGroup()
    {
    }

    /**
     * @return null|string
     */
    public function getClassName()
    {
    }

    /**
     * @return null|string
     */
    public function getPropertyName()
    {
    }

    /**
     * @param string $subPath
     * @return string|void
     */
    public function getPropertyPath($subPath = '')
    {
    }
}
