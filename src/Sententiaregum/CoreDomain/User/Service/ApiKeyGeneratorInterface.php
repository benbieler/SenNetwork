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

/**
 * Interface which provides a method to generate safe api keys
 */
interface ApiKeyGeneratorInterface
{
    /**
     * Generates an api key
     *
     * @return string
     */
    public function generate();
}
