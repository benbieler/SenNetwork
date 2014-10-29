<?php
namespace Ma27\SocialNetworkingBundle\Service;

use Ma27\SocialNetworkingBundle\Entity\User\Api\UserRepositoryInterface;
use Ma27\SocialNetworkingBundle\Service\Api\TokenInterface;

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
