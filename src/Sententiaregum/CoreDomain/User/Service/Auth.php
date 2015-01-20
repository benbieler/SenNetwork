<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\CoreDomain\User\Service;

use Sententiaregum\CoreDomain\User\DTO\AuthDTO;
use Sententiaregum\CoreDomain\User\Event\AuthEvent;
use Sententiaregum\CoreDomain\User\UserAggregateRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Concrete auth implementation
 */
class Auth implements AuthInterface
{
    /**
     * @var UserAggregateRepository
     */
    private $repository;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @param UserAggregateRepository $repository
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(UserAggregateRepository $repository, EventDispatcherInterface $dispatcher)
    {
        $this->repository      = $repository;
        $this->eventDispatcher = $dispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function authenticateUser(AuthDTO $credentials)
    {
        $user = $this->repository->findOneByName($credentials->getUsername());
        if (!$user) {
            return (new AuthEvent())->setFailReason('Invalid credentials!');
        }

        return $user->authenticate($credentials, $this->eventDispatcher);
    }
}
