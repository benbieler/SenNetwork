<?php

namespace Sententiaregum\Bundle\UserBundle\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

class UniqueEmailConstraint extends Constraint
{
    public $message = 'Email {{ address }} already in use';

    public function validatedBy()
    {
        return 'unique_email_constraint';
    }
}
