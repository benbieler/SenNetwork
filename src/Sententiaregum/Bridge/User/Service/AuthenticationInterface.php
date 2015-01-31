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

use Sententiaregum\Bridge\User\DTO\AuthDTO;

/**
 * Interface of a service which provides a user authentication
 */
interface AuthenticationInterface
{
    /**
     * @var string
     */
    const AUTHENTICATION_SUCCESSFUL_EVENT = 'sen.domain.user.auth.success';

    /**
     * Sets the ip of the requester
     *
     * @param string $requesterIp
     *
     * @return $this
     */
    public function setRequesterIp($requesterIp);

    /**
     * Authenticates the user
     *
     * @param AuthDTO $authDTO
     *
     * @return \Sententiaregum\Bridge\User\Event\AuthEvent
     */
    public function signIn(AuthDTO $authDTO);

    /**
     * Signs a user out and returns its id
     *
     * @param integer $userId
     *
     * @return integer
     */
    public function signOut($userId);
}
