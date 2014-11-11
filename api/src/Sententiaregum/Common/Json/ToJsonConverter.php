<?php

namespace Sententiaregum\Common\Json;

use Sententiaregum\Common\Api\ToJsonInterface;

class ToJsonConverter implements ToJsonInterface
{
    public function toJson($object, array $props)
    {
        $r = new \ReflectionObject($object);

        $data = [];
        foreach ($props as $property) {
            $prop = $r->getProperty($property);

            // mark all properties as accessible to get their values with a reflection
            $prop->setAccessible(true);

            $data[$property] = $prop->getValue($object);
        }

        return json_encode($data);
    }
}
