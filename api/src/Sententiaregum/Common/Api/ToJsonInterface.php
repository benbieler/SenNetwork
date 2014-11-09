<?php

namespace Sententiaregum\Common\Api;

interface ToJsonInterface
{
    public function toJson($object, array $props);
}
