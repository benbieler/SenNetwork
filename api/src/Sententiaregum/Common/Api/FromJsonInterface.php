<?php

namespace Sententiaregum\Common\Api;

interface FromJsonInterface
{
    /**
     * @param string $jsonString
     * @return mixed
     */
    public static function createFromJson($jsonString);
}
