<?php

namespace Sententiaregum\Bundle\RedisQueueBundle;

class SententiaregumQueueBundleEvents
{
    /**
     * In this event all cached items contained by the
     * redis storage will be refreshed or changed
     *
     * @var string
     */
    const REDIS_WARM_UP_QUEUE = 'sen.redis.warm_up';
}
 