<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\UserBundle\Security;

use Sententiaregum\Bundle\UserBundle\Security\Api\TokenInterface;
use Sententiaregum\Bundle\UserBundle\Entity\Api\UserRepositoryInterface;

class Token implements TokenInterface
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
     * @return UserRepositoryInterface
     */
    protected function getUserRepository()
    {
        return $this->userRepository;
    }

    /**
     * @return string
     */
    public function generateToken()
    {
        // use sensio distribution bundle implementation
        return hash('sha1', uniqid(mt_rand(), true));
    }

    /**
     * @param integer $id
     * @return string
     * @throws \OverflowException
     */
    public function storeToken($id)
    {
        $count = 0;
        $max = 25;

        do {
            if ($count === $max) {
                throw new \OverflowException('Too many loops!');
            }

            $token = $this->generateToken();
            $result = $this->userRepository->storeToken($token, $id);
            $count++;
        } while (!$result);

        return $token;
    }
}
