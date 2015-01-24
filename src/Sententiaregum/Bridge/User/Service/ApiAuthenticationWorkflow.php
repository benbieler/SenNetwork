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

use Psr\Log\LoggerInterface;
use Sententiaregum\CoreDomain\User\DTO\AuthDTO;
use Sententiaregum\CoreDomain\User\Event\AuthEvent;
use Sententiaregum\CoreDomain\User\Service\AuthInterface;
use Sententiaregum\CoreDomain\User\UserAggregateRepositoryInterface;

/**
 * Implementation of a authentication workflow
 */
class ApiAuthenticationWorkflow
{
    /**
     * @var AuthInterface
     */
    private $auth;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var UserAggregateRepositoryInterface
     */
    private $userRepository;

    /**
     * @var string
     */
    private $ip;

    /**
     * @param AuthInterface $auth
     * @param LoggerInterface $logger
     * @param UserAggregateRepositoryInterface $userRepo
     */
    public function __construct(AuthInterface $auth, LoggerInterface $logger, UserAggregateRepositoryInterface $userRepo)
    {
        $this->auth           = $auth;
        $this->logger         = $logger;
        $this->userRepository = $userRepo;
    }

    /**
     * Sets the ip of the requester
     *
     * @param string $ip
     *
     * @return $this
     */
    public function setRequesterIp($ip)
    {
        $this->ip = (string) $ip;

        return $this;
    }

    /**
     * Authenticates a user
     *
     * @param AuthDTO $credentials
     *
     * @return \Sententiaregum\CoreDomain\User\Event\AuthEvent
     */
    public function authenticate(AuthDTO $credentials)
    {
        $event = $this->auth->authenticateUser($credentials);
        if ($event->isFailed()) {
            $this->logger->notice($this->createLoggerMessage($credentials, $event));

            return $event;
        }

        $event->getUser()->createToken();
        $this->userRepository->update($event->getUser());

        return $event;
    }

    /**
     * Generates a logger message
     *
     * @param AuthDTO $credentials
     * @param AuthEvent $result
     *
     * @return string
     */
    private function createLoggerMessage(AuthDTO $credentials, AuthEvent $result)
    {
        $messagePart = sprintf(
            'Someone tried to authenticate with invalid credentials the account with username %s! Reason: %s.',
            $credentials->getUsername(),
            $result->getFailReason()
        );

        if ($this->ip) {
            $messagePart .= sprintf(' The ip was: %s', $this->ip);
        }

        return $messagePart;
    }
}
