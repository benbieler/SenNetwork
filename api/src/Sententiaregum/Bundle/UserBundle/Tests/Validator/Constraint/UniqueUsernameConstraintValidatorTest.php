<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\UserBundle\Tests\Validator\Constraint;

use Sententiaregum\Bundle\CommonBundle\Test\Stub\ConstraintContextFixture;
use Sententiaregum\Bundle\UserBundle\Entity\Api\UserInterface;
use Sententiaregum\Bundle\UserBundle\Entity\Api\UserRepositoryInterface;
use Sententiaregum\Bundle\UserBundle\Validator\Constraint\UniqueUsernameConstraint;
use Sententiaregum\Bundle\UserBundle\Validator\Constraint\UniqueUsernameConstraintValidator;
use Symfony\Component\Validator\Constraint;

class UniqueUsernameConstraintValidatorTest extends \PHPUnit_Framework_TestCase 
{
    /**
     * @var UniqueUsernameConstraintValidator
     */
    private $constraintValidator;

    public function setUp()
    {
        $userRepository = $this->getMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->any())
            ->method('findByName')
            ->will($this->returnValue(
                $this->getMock(UserInterface::class)
            ));

        $this->constraintValidator = new UniqueUsernameConstraintValidator($userRepository);
    }
    
    public function tearDown()
    {
        $this->constraintValidator = null;
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\UnexpectedTypeException
     */
    public function testInvalidConstraint()
    {
        $this->constraintValidator->validate(null, $this->getMock(Constraint::class));
    }

    public function testConstraintValidator()
    {
        $fixture = new ConstraintContextFixture();
        $this->constraintValidator->initialize($fixture);
        $this->constraintValidator->validate('John Doe', new UniqueUsernameConstraint());

        $this->assertGreaterThan(0, count($fixture->getViolations()));
    }
}
