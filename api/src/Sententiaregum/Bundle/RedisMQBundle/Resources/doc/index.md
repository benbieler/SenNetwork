Redis MQ (Message Queue) Bundle
===============================

This bundle can enqueue items into redis using *SncRedisBundle* and *Predis*

1) Enqueue items
----------------

A little code example to enqueue items:

    $queueContext = new \Sententiaregum\Bundle\RedisMQBundle\Service\QueueContext;
    $value = new Sententiaregum\Bundle\RedisMQBundle\Entity\QueueEntity(['your value']);
    $queueContext->push($value); // push the value to a temporary stack
    $queueContext->enqueue();    // push the stack into redis
    
#### Entity values

The entity values should do some of the following points:

  - being convertable to array
  - implement \Traversable
  - extend \ArrayObject
  - implement \JsonSerializable


2) Dequeue items
----------------

When the items are pushed into redis, you can dequeue them simply:

    $queueContext = new \Sententiaregum\Bundle\RedisMQBundle\Service\QueueContext;
    while ($entity = $queueContext->pop()) {
        $value = $entity->getValue();
        var_dump($value);
    }
    
When you have execute the code from [above](#enqueue-items), you should get following output now:

    array(1) {
      [0]=>
      string(10) 'your value'
    }


3) Queue namespaces
-------------------

When you push your items into redis, they will have the prefix *redis::queue::* by default.

If you'd like to have multiple queues, just switch the prefixes:

    $queueContext = new \Sententiaregum\Bundle\RedisMQBundle\Service\QueueContext;
    $queueContext->setQueueNamespace('your::custom::redis::namespace::');


4) Symfony 2
------------

You can use this bundle with Symfony 2.

*app/config.yml*

    sententiaregum_redis_mq:
      queues:
        default: your::redis::namespace::


Now you can access the queue via the container:

    $queue = $this->container->get('sen.redis_queue.context.default');
