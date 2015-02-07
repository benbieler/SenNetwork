<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Domain\User;

/**
 * Value object containing an api token
 */
class Token
{
    /**
     * @var integer
     */
    private $tokenId;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var User
     */
    private $user;

    /**
     * @param string $apiKey
     * @param User $user
     */
    public function __construct($apiKey, User $user = null)
    {
        $this->apiKey  = (string) $apiKey;
        $this->user    = $user;
    }

    /**
     * @return integer
     */
    public function getTokenId()
    {
        return $this->tokenId;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}
