<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Sententiaregum\Domain\User\User;
use Sententiaregum\Domain\User\UserReadRepositoryInterface;
use Sententiaregum\Domain\User\UserWriteRepositoryInterface;

/**
 * Concrete implementation of the user repository
 */
class UserRepository extends EntityRepository implements UserReadRepositoryInterface, UserWriteRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function findOneByName($username)
    {
        return $this->findOneBy(['username' => $username]);
    }

    /**
     * {@inheritdoc}
     */
    public function findOneByApiKey($apiKey)
    {
        $result = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('t')
            ->from('SEN_User:Token', 't')
            ->where('t.apiKey = :apiKey')
            ->setParameter('apiKey', $apiKey)
            ->getQuery()
            ->getSingleResult();

        /** @var $result \Sententiaregum\Domain\User\Token */
        return $result->getUser();
    }

    /**
     * {@inheritdoc}
     */
    public function add(User $user)
    {
        $entityManager = $this->getEntityManager();

        if (null !== $token = $user->getToken()) {
            $entityManager->persist($token);
        }

        foreach ($user->getRoles() as $role) {
            $entityManager->persist($role);
        }

        $entityManager->persist($user);
        $entityManager->flush();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function update(User $user)
    {
        $entityManager = $this->getEntityManager();

        // check if the user is persistent
        /** @var \Sententiaregum\Domain\User\User $loadedUser */
        if (!$user->getUsername() || null === $loadedUser = $this->find($user->getUserId())) {
            $this->add($user);

            return $this;
        }

        // update token
        if (null !== $token = $user->getToken()) {
            if (null !== $id = $token->getTokenId()) {
                $persistedToken = $entityManager->find('SEN_User:Token', $id);

                if (!$persistedToken) {
                    $entityManager->merge($token);
                } else {
                    $entityManager->persist($token);
                }
            } else {
                $entityManager->persist($token);
            }
        }

        // update roles
        // note: roles should not be updated since these are immutable value objects
        foreach ($user->getRoles() as $role) {
            $exists = false;
            foreach ($loadedUser->getRoles() as $persistedRole) {
                if ($persistedRole->getId() === $role->getId() && null !== $role->getId()) {
                    $exists = true;
                    break;
                }
            }

            if (!$exists) {
                $entityManager->persist($role);
            }
        }

        $entityManager->merge($user);
        $entityManager->flush();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(User $user)
    {
        $entityManager = $this->getEntityManager();

        // remove elements from user relations
        $token = $user->getToken();
        $user->removeToken();
        $user->removeRoles();
        $user->removeFollowers();
        $user->unfollowMany();

        // remove user entity
        $entityManager->merge($user);
        $entityManager->remove($user);

        if (null !== $token) {
            $entityManager->remove($token);
        }

        $entityManager->flush();

        return $this;
    }
}
