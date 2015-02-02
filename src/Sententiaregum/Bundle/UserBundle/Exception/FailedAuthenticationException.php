<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\UserBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class FailedAuthenticationException extends HttpException
{
    /**
     * @param string $message
     * @param \Exception $previous
     * @param array $headers
     * @param integer $code
     */
    public function __construct(
        $message = null,
        \Exception $previous = null,
        array $headers = array(),
        $code = 0
    ) {
        parent::__construct(401, $message, $previous, $headers, $code);
    }
}
