<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bridge\User\Validator\Constraint;

use Doctrine\Common\Annotations\Annotation\Target;
use Symfony\Component\Validator\Constraint;

/**
 * Constraint which validates the uniqueness of a user
 *
 * @Annotation
 * @Target({"PROPERTY", "ANNOTATION"})
 */
class UniqueUser extends Constraint
{
    /**
     * @var string
     */
    public $entity = 'SEN_User:User';

    /**
     * @var string
     */
    public $property;

    /**
     * @var string
     */
    public $message = 'Property {{ prop }} is not unique!';

    /**
     * @var string
     */
    public $validator = 'sen.user.validator.unique_user.doctrine';

    /**
     * @var string
     */
    public $em = 'default';

    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return $this->validator;
    }
}
