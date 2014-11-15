<?php

namespace Sententiaregum\Bundle\RedisMQBundle\Api;

interface QueueNamespaceInterface
{
    /**
     * @param string $namespace
     * @return void
     */
    public function setQueueNamespace($namespace);

    /**
     * @return string
     */
    public function getQueueNamespace();
}
 