<?php

namespace Sententiaregum\Bundle\UserBundle\Service;

use Sententiaregum\Bundle\UserBundle\Entity\User;

interface CreateAccountInterface
{
    /**
     * @param User $user
     * @return void
     */
    public function persist(User $user);

    /**
     * @param User $user
     * @return string[]
     */
    public function validateInput(User $user);
}
 