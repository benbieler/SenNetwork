<?php

namespace Sententiaregum\Bundle\RedisQueueBundle\Service;

use Sententiaregum\Bundle\RedisQueueBundle\Entity\CachedSqlResult;
use Sententiaregum\Bundle\RedisQueueBundle\Event\RedisQueueWarmUpEvent;
use Sententiaregum\Bundle\RedisQueueBundle\SententiaregumQueueBundleEvents;
use SplDoublyLinkedList;
use SplObjectStorage;
use SplQueue;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class RedisQueue implements RedisQueueInterface
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var SplQueue
     */
    private $entityStorage;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
        $this->entityStorage = new SplQueue();
    }

    /**
     * @param CachedSqlResult $entity
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function attach(CachedSqlResult $entity)
    {
        if ($this->has($entity->getQuery(), $entity->getParameters())) {
            throw new \InvalidArgumentException(
                sprintf('Entity with sql query (%s) already enqueued!', $entity->getQuery())
            );
        }

        $this->entityStorage->enqueue($entity);
        return $this;
    }

    /**
     * @param CachedSqlResult[] $entityStack
     * @return $this
     */
    public function add(array $entityStack)
    {
        array_map([$this, 'attach'], $entityStack);
        return $this;
    }

    /**
     * @param string $query
     * @param mixed[] $parameters
     * @return boolean
     */
    public function has($query, array $parameters = [])
    {
        /** @var CachedSqlResult $cachedItem */
        foreach (iterator_to_array($this->entityStorage) as $cachedItem) {
            if ($query === $cachedItem->getQuery() && $parameters === $cachedItem->getParameters()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param mixed[] $jobParams
     * @return SplObjectStorage
     */
    public function progress(array $jobParams = [])
    {
        $this->entityStorage->setIteratorMode(SplDoublyLinkedList::IT_MODE_DELETE);

        $event = new RedisQueueWarmUpEvent();
        $event->attributes->add($jobParams);

        $storage = new SplObjectStorage();

        /** @var CachedSqlResult $resultItem */
        foreach ($this->entityStorage as $resultItem) {
            $concreteEvent = clone $event;
            $concreteEvent->setEntity($resultItem);

            /** @var RedisQueueWarmUpEvent $event */
            $event = $this->dispatcher->dispatch(SententiaregumQueueBundleEvents::REDIS_WARM_UP_QUEUE, $concreteEvent);
            $storage->attach($event->getEntity());
        }

        return $storage;
    }
}
