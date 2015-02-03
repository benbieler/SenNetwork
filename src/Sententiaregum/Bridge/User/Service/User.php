<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bridge\User\Service;

use Doctrine\ORM\EntityManagerInterface;
use Sententiaregum\Bridge\User\DTO\CreateUserDTO;
use Sententiaregum\Bridge\User\Event\UserCreatedEvent;
use Sententiaregum\CoreDomain\User\User as UserEntity;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Implementation of a CRUD user service
 */
class User implements UserInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @param EntityManagerInterface $entityManager
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EntityManagerInterface $entityManager, EventDispatcherInterface $eventDispatcher)
    {
        $this->entityManager = $entityManager;
        $this->dispatcher    = $eventDispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function create(CreateUserDTO $createUser)
    {
        $userEntity = new UserEntity(
            $createUser->getUsername(),
            $createUser->getPassword(),
            $createUser->getEmail()
        );

        array_map(
            function ($role) use ($userEntity) {
                $userEntity->addRole($role);
            },
            $createUser->getRoles()
        );

        $this->entityManager->persist($userEntity);
        $this->dispatcher->dispatch(self::USER_CREATED_EVENT, new UserCreatedEvent($userEntity));

        return true;
    }
}
