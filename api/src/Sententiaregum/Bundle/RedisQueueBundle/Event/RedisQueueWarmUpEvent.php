<?php

namespace Sententiaregum\Bundle\RedisQueueBundle\Event;

use Sententiaregum\Bundle\RedisQueueBundle\Entity\CachedSqlResult;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\EventDispatcher\Event;

class RedisQueueWarmUpEvent extends Event
{
    /**
     * @var CachedSqlResult
     */
    private $entity;

    /**
     * @var ParameterBag
     */
    public $attributes;

    public function __construct()
    {
        $this->attributes = new ParameterBag();
    }

    /**
     * @param CachedSqlResult $result
     * @return $this
     */
    public function setEntity(CachedSqlResult $result)
    {
        $this->entity = $result;
        return $this;
    }

    /**
     * @return CachedSqlResult
     */
    public function getEntity()
    {
        return $this->entity;
    }
}
 