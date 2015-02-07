<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\UserBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Sententiaregum\Bundle\UserBundle\DTO\AuthDTO;
use Sententiaregum\Bundle\UserBundle\Event\AuthEvent;
use Sententiaregum\Domain\User\Service\ApiKeyGeneratorInterface;
use Sententiaregum\Domain\User\UserReadRepositoryInterface;
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
     * @var UserReadRepositoryInterface
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
     * @var ApiKeyGeneratorInterface
     */
    private $apiKeyGenerator;

    /**
     * @var string
     */
    private $requesterIp;

    /**
     * @param UserReadRepositoryInterface $userRepository
     * @param ApiKeyGeneratorInterface $apiKeyGenerator
     * @param EventDispatcherInterface $eventDispatcher
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface $logger
     */
    public function __construct(
        UserReadRepositoryInterface $userRepository,
        ApiKeyGeneratorInterface $apiKeyGenerator,
        EventDispatcherInterface $eventDispatcher,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger
    ) {
        $this->logger          = $logger;
        $this->apiKeyGenerator = $apiKeyGenerator;
        $this->userRepository  = $userRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->entityManager   = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function setRequesterIp($requesterIp)
    {
        $this->requesterIp = (string) $requesterIp;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function signIn(AuthDTO $authDTO)
    {
        $userEntity = $this->userRepository->findOneByName($authDTO->getUsername());

        if (!$userEntity) {
            return $this->handleAuthFailure($authDTO);
        }
        if (!$userEntity->getPassword()->compare($authDTO->getPassword())) {
            return $this->handleAuthFailure($authDTO);
        }

        $event = new AuthEvent($userEntity);

        /** @var \Sententiaregum\Bundle\UserBundle\Event\AuthEvent $dispatchedEvent */
        $dispatchedEvent = $this->eventDispatcher->dispatch(self::AUTHENTICATION_SUCCESSFUL_EVENT, $event);
        if (null !== $dispatchedEvent && $dispatchedEvent->isFailed()) {
            return $dispatchedEvent;
        }

        $userEntity->createToken($this->apiKeyGenerator);

        $this->entityManager->persist($userEntity->getToken());

        // since the entity is already persisted the modified entity should be merged only
        $this->entityManager->merge($userEntity);

        // if the dispatcher returns nothing,
        // the original event will be returned
        return $dispatchedEvent ?: $event;
    }

    /**
     * Handles the failed authentication
     *
     * @param \Sententiaregum\Bundle\UserBundle\DTO\AuthDTO $authDTO
     *
     * @return \Sententiaregum\Bundle\UserBundle\Event\AuthEvent
     */
    private function handleAuthFailure(AuthDTO $authDTO)
    {
        #ToDo: dispatch "auth failed" event
        $errorEvent = new AuthEvent();
        $errorEvent->fail('Invalid credentials');

        $logEntry = sprintf(
            'Someone tried to authenticate with invalid credentials the account %s! Reason: %s! The ip was %s',
            $authDTO->getUsername(),
            $errorEvent->getFailReason(),
            $this->requesterIp ?: 'unknown'
        );

        $this->logger->notice($logEntry);

        return $errorEvent;
    }

    /**
     * {@inheritdoc}
     */
    public function signOut($userId)
    {

    }
}