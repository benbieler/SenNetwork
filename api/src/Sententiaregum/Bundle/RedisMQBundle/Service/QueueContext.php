<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\RedisMQBundle\Service;

use Predis\Client;
use Sententiaregum\Bundle\RedisMQBundle\Api\QueueInputInterface;
use Sententiaregum\Bundle\RedisMQBundle\Api\QueueOutputInterface;
use Sententiaregum\Bundle\RedisMQBundle\Entity\QueueEntity;

class QueueContext implements QueueInputInterface, QueueOutputInterface
{
    /**
     * @var string
     */
    private $redisNamespace = 'redis::queue::';

    /**
     * @var Client
     */
    private $client;

    /**
     * @var \SplObjectStorage
     */
    private $queueStack;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->queueStack = new \SplObjectStorage();
    }

    /**
     * @return QueueEntity
     */
    public function pop()
    {
        $keys = $this->client->keys($this->redisNamespace . '*');
        if (0 === count($keys)) {
            return null;
        }

        $current = $keys[count($keys) - 1];
        $json = $this->client->get($current);
        $this->client->del($current);

        return QueueEntity::createFromJson($json);
    }

    /**
     * @return void
     */
    public function getGenerator()
    {
        foreach ($this->client->keys($this->redisNamespace . '*') as $key) {
            $serializedString = $this->client->get($key);

            // dequeue item
            $this->client->del($key);

            yield QueueEntity::createFromJson($serializedString);
        }
    }

    /**
     * @param QueueEntity $queueEntity
     * @return void
     */
    public function push(QueueEntity $queueEntity)
    {
        $this->queueStack->attach($queueEntity, uniqid(mt_rand()));
    }

    /**
     * @return void
     */
    public function enqueue()
    {
        /** @var QueueEntity $entity */
        foreach ($this->queueStack as $entity) {
            $id = $this->queueStack->offsetGet($entity);
            $this->client->set($this->redisNamespace . (string) $id, json_encode($entity));
        }
    }

    /**
     * @param string $namespace
     * @return void
     */
    public function setQueueNamespace($namespace)
    {
        $this->redisNamespace = (string) $namespace;
    }

    /**
     * @return string
     */
    public function getQueueNamespace()
    {
        return $this->redisNamespace;
    }
}
