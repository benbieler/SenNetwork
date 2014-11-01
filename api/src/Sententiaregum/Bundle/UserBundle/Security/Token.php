<?php

namespace Sententiaregum\Bundle\UserBundle\Security;

use Sententiaregum\Bundle\UserBundle\Security\Api\TokenInterface;
use Sententiaregum\Bundle\UserBundle\Entity\Api\UserRepositoryInterface;

class Token implements TokenInterface
{
    /**
     * @var UserRepositoryInterface
     */
    protected $userRepository;

    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
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
