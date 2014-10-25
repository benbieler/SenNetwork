<?php
namespace Ma27\SocialNetworkingBundle\Service;

use Ma27\SocialNetworkingBundle\Entity\User\Api\UserRepositoryInterface;
use Ma27\SocialNetworkingBundle\Service\Api\TokenInterface;
use Symfony\Component\Security\Core\Util\SecureRandom;

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
        $generator = new SecureRandom();
        return $generator->nextBytes(255);
    }

    /**
     * @param string $token
     * @param integer $id
     * @return boolean
     * @throws \OverflowException
     */
    public function storeToken($token, $id)
    {
        $count = 0;
        $max = 20;

        do {
            if ($count === $max) {
                throw new \OverflowException('Too many loops!');
            }

            $result = $this->userRepository->storeToken($token, $id);
            $count++;
        } while (!$result);

        return true;
    }
}
