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

use Sententiaregum\Bundle\UserBundle\Entity\Api\UserRepositoryInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
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
     * @param string $username
     * @return \Sententiaregum\Bundle\UserBundle\Entity\User\Api\UserInterface
     */
    public function loadUserByUsername($username)
    {
        return $this->userRepository->findByName($username);
    }

    /**
     * @param UserInterface $user
     * @return \Sententiaregum\Bundle\UserBundle\Entity\User\Api\UserInterface
     * @throws UnsupportedUserException
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
     * @return \Sententiaregum\Bundle\UserBundle\Entity\User
     */
    public function findUserByApiToken($apiKey)
    {
        $id = $this->userRepository->findUserIdByApiToken($apiKey);
        if (!$id) {
            return;
        }

        return $this->userRepository->findById($id);
    }

    /**
     * @param integer $userId
     * @return string
     */
    public function findApiKeyByUserId($userId)
    {
        return $this->userRepository->findApiTokenByUserId($userId);
    }
}
