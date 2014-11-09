<?php

namespace spec\Sententiaregum\Bundle\RedisQueueBundle\Service;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sententiaregum\Bundle\RedisQueueBundle\Entity\CachedSqlResult;
use Sententiaregum\Bundle\RedisQueueBundle\Event\RedisQueueWarmUpEvent;
use Sententiaregum\Bundle\RedisQueueBundle\SententiaregumQueueBundleEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class RedisQueueSpec extends ObjectBehavior
{
    function let(EventDispatcherInterface $dispatcher)
    {
        $this->beConstructedWith($dispatcher);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sententiaregum\Bundle\RedisQueueBundle\Service\RedisQUeue');
    }

    function it_processes_the_queue(EventDispatcherInterface $dispatcher)
    {
        $dispatcher->dispatch(
            SententiaregumQueueBundleEvents::REDIS_WARM_UP_QUEUE,
            Argument::that(function ($arg) {
                return $arg instanceof RedisQueueWarmUpEvent;
            })
        )->will(function ($args) {
            return $args[1];
        });

        $entities = [
            new CachedSqlResult('SELECT * FROM `foo`', []),
            new CachedSqlResult('SELECT * FROM `bar`', [])
        ];
        $this->add($entities);

        $result = $this->progress();
        $result->shouldBeAnInstanceOf(\SplObjectStorage::class);
        $result->shouldHaveCount(2);
    }

    function it_throws_exception_if_two_equal_cache_result_exists()
    {
        $entity = new CachedSqlResult('SELECT * FROM `foo`', []);
        $this->attach($entity);

        $this->shouldThrow(\InvalidArgumentException::class)->duringAttach(clone $entity);
    }
}
