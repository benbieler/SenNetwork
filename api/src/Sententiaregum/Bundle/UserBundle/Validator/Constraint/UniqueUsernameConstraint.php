<?php

namespace Sententiaregum\Bundle\UserBundle\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

class UniqueUsernameConstraint extends Constraint
{
    public $message = 'Username {{ name }} already in use';

    public function validatedBy()
    {
        return 'unique_user_constraint';
    }
}
