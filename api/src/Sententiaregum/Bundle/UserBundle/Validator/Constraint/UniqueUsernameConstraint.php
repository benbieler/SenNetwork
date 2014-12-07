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

use Symfony\Component\Validator\Constraint;

class UniqueUsernameConstraint extends Constraint
{
    public $message = 'Username {{ name }} already in use';

    /**
     * @return string
     */
    public function validatedBy()
    {
        return 'unique_user_constraint';
    }
}
