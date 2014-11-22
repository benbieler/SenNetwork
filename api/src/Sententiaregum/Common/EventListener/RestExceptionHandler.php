<?php

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
            $buffer[$property->getName()] = $property->getValue();
        }

        return $buffer;
    }
}
