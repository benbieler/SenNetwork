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

use Sententiaregum\Domain\User\Exception\UserDomainException;

/**
 * Value object containing a password
 */
class Password
{
    /**
     * @var string
     */
    private $password;

    /**
     * @var boolean
     */
    private $isHashed;

    /**
     * @param string $password
     * @param boolean $automateHash
     */
    public function __construct($password, $automateHash = false)
    {
        $this->password = (string) $password;

        $info           = password_get_info($this->password);
        $this->isHashed = 'unknown' !== $info['algoName'];

        if ($automateHash) {
            $this->hash();
        }
    }

    /**
     * Returns the raw value of the password property
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Returns the current password hash
     *
     * @return string
     */
    public function getHash()
    {
        $this->hash();

        return $this->password;
    }

    /**
     * Compares the hash with a raw password
     *
     * @param string $raw
     *
     * @return boolean
     */
    public function compare($raw)
    {
        return password_verify($raw, $this->getHash());
    }

    /**
     * Checks if the password is hashed
     *
     * @return boolean
     */
    public function isHashed()
    {
        return $this->isHashed;
    }

    /**
     * Hashes the internal password
     *
     * @return $this
     *
     * @throws \Sententiaregum\Domain\User\Exception\UserDomainException If the password_hash() function returns false
     */
    public function hash()
    {
        if (!$this->isHashed()) {
            $this->isHashed = true;
            $password       = password_hash($this->password, PASSWORD_BCRYPT);

            if (!$password) {
                throw new UserDomainException('Cannot hash password since password_hash() returns false!');
            }

            $this->password = $password;
        }

        return $this;
    }

    /**
     * Returns the string representation of the password
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getPassword();
    }
}
