<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\CoreDomain\User\Tests\Service;

use Sententiaregum\CoreDomain\User\Service\ApiKeyGenerator;

class ApiKeyGeneratorTest extends \PHPUnit_Framework_TestCase 
{
    public function testTokenGeneration()
    {
        $generator = new ApiKeyGenerator();

        $key = $generator->generate();

        $this->assertTrue(ctype_print($key));
        $this->assertSame(200, strlen($key));
    }
}
