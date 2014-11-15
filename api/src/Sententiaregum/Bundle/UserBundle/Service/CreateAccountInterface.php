<?php

namespace Sententiaregum\Bundle\UserBundle\Service;

use Sententiaregum\Bundle\UserBundle\Entity\Api\UserInterface;

interface CreateAccountInterface
{
    /**
     * @param UserInterface $user
     * @return void
     */
    public function persist(UserInterface $user);

    /**
     * @param UserInterface $user
     * @return string[]
     */
    public function validateInput(UserInterface $user);
}
