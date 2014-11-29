<?php

namespace Sententiaregum\Bundle\RedisMQBundle\Api;

interface QueueOutputInterface extends QueueNamespaceInterface
{
    public function pop();
    public function getGenerator();
}
