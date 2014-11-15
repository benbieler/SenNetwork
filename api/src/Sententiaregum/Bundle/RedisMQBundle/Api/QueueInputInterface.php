<?php

namespace Sententiaregum\Bundle\RedisMQBundle\Api;

use Sententiaregum\Bundle\RedisMQBundle\Entity\QueueEntity;

interface QueueInputInterface extends QueueNamespaceInterface
{
    /**
     * @param QueueEntity $queueEntity
     * @return void
     */
    public function push(QueueEntity $queueEntity);

    /**
     * @return void
     */
    public function enqueue();
}
