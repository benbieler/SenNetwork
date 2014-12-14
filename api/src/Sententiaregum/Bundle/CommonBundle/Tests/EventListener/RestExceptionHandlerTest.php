<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\CommonBundle\Tests\EventListener;

use Sententiaregum\Bundle\CommonBundle\EventListener\RestExceptionHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class RestExceptionHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testExceptionConverter()
    {
        $exception = new \Exception('Exception message', 123);

        $converter = new RestExceptionHandler();
        $event = $this->createEvent($exception);

        $converter->onException($event);
        $this->assertInstanceOf(JsonResponse::class, $data = $event->getResponse());

        $converted = json_decode($data->getContent(), true);
        $this->assertSame(JSON_ERROR_NONE, json_last_error());

        $this->assertArrayHasKey('exception', $converted);
        $exceptionDetails = $converted['exception'];

        $this->assertSame('Exception message', $exceptionDetails['message']);
        $this->assertSame(123, $exceptionDetails['code']);
    }

    /**
     * @param \Exception $exception
     * @return GetResponseForExceptionEvent
     */
    private function createEvent(\Exception $exception)
    {
        return new GetResponseForExceptionEvent(
            $this->getMock(HttpKernelInterface::class),
            Request::create('/'),
            HttpKernelInterface::MASTER_REQUEST,
            $exception
        );
    }
}
 