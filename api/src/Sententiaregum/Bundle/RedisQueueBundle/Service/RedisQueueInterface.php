<?php

namespace Sententiaregum\Bundle\RedisQueueBundle\Service;

use Sententiaregum\Bundle\RedisQueueBundle\Entity\CachedSqlResult;

interface RedisQueueInterface
{
    public function attach(CachedSqlResult $entity);
    public function add(array $entityStack);
    public function has($query);
    public function progress();
}
