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

use Sententiaregum\Domain\User\User;
use Sententiaregum\Domain\User\UserReadRepositoryInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User provider
 */
class UserProvider implements AdvancedUserProviderInterface
{
    /**
     * @var \Sententiaregum\Domain\User\UserReadRepositoryInterface
     */
    private $userRepository;

    /**
     * @param \Sententiaregum\Domain\User\UserReadRepositoryInterface $userRepository
     */
    public function __construct(UserReadRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function findUserByApiKey($apiKey)
    {
        return $this->userRepository->findOneByApiKey($apiKey);
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username)
    {
        return $this->userRepository->findOneByName($username);
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$this->supportsClass(get_class($user))) {
            throw new UnsupportedUserException;
        }

        return $this->userRepository->findOneByName($user->getUsername());
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        return User::class === $class;
    }
}
