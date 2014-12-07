<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Common\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class RestExceptionHandler
{
    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        if ($exception instanceof \JsonSerializable) {
            $data = $exception->jsonSerialize();
        } else if ($exception instanceof \Traversable) {
            $data = iterator_to_array($exception);
        } else {
            $data = $this->convertPhpExceptionToJson($exception);
        }

        $event->setResponse(new JsonResponse(['exception' => $data]));
    }

    /**
     * @param \Exception $exception
     * @return mixed[]
     */
    private function convertPhpExceptionToJson(\Exception $exception)
    {
        $buffer = [];

        $r = new \ReflectionObject($exception);
        foreach ($r->getProperties() as $property) {
            $property->setAccessible(true);
            $buffer[$property->getName()] = $property->getValue($exception);
        }

        return $buffer;
    }
}
