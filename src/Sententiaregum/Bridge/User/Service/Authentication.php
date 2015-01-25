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
use Psr\Log\LoggerInterface;
use Sententiaregum\Bridge\User\DTO\AuthDTO;
use Sententiaregum\Bridge\User\Event\AuthEvent;
use Sententiaregum\CoreDomain\User\UserAggregateRepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Simple implementation of an authentication service
 */
class Authentication implements AuthenticationInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var UserAggregateRepositoryInterface
     */
    private $userRepository;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var string
     */
    private $ip;

    /**
     * @param UserAggregateRepositoryInterface $userRepository
     * @param EventDispatcherInterface $eventDispatcher
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface $logger
     */
    public function __construct(
        UserAggregateRepositoryInterface $userRepository,
        EventDispatcherInterface $eventDispatcher,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger
    ) {
        $this->logger          = $logger;
        $this->userRepository  = $userRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->entityManager   = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function setRequesterIp($ip)
    {
        $this->ip = (string) $ip;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function signIn(AuthDTO $authDTO)
    {
        $userEntity = $this->userRepository->findOneByName($authDTO->getUsername());

        if (!$userEntity) {
            $errorEvent = new AuthEvent();
            $errorEvent->fail('Invalid credentials');

            $logEntry = sprintf(
                'Someone tried to authenticate with invalid credentials the account %s! Reason: %s! The ip was %s',
                $authDTO->getUsername(),
                $errorEvent->getFailReason(),
                $this->ip ?: 'unknown'
            );

            $this->logger->notice($logEntry);

            return $errorEvent;
        }

        $event = new AuthEvent($userEntity);

        /** @var AuthEvent $dispatchedEvent */
        $dispatchedEvent = $this->eventDispatcher->dispatch(self::AUTHENTICATION_SUCCESSFUL_EVENT, $event);
        if (null !== $dispatchedEvent && $dispatchedEvent->isFailed()) {
            return $dispatchedEvent;
        }

        $userEntity->createToken();
        $this->entityManager->merge($userEntity);
        $this->entityManager->flush();

        return $dispatchedEvent ?: $event;
    }

    /**
     * {@inheritdoc}
     */
    public function signOut($userId)
    {
        # TODO: Implement signOut() method.
    }
}
