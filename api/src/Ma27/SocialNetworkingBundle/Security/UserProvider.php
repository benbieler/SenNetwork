<?php
namespace Ma27\SocialNetworkingBundle\Security;

use Ma27\SocialNetworkingBundle\Entity\User\Api\UserRepositoryInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
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
     * @param string $username
     * @return \Ma27\SocialNetworkingBundle\Entity\User\Api\UserInterface
     */
    public function loadUserByUsername($username)
    {
        return $this->userRepository->findByName($username);
    }

    /**
     * @param UserInterface $user
     * @return \Ma27\SocialNetworkingBundle\Entity\User\Api\UserInterface
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$this->supportsClass(get_class($user))) {
            throw new UnsupportedUserException;
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * @param string $class
     * @return boolean
     */
    public function supportsClass($class)
    {
        return $class instanceof UserInterface;
    }

    /**
     * @param string $apiKey
     * @return \Ma27\SocialNetworkingBundle\Entity\User\Api\UserInterface
     */
    public function findUserByApiToken($apiKey)
    {
        $id = $this->userRepository->findUserIdByApiToken($apiKey);
        if (!$id) {
            return;
        }

        return $this->userRepository->findById($id);
    }
}
