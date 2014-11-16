<?php

namespace Sententiaregum\Bundle\RedisMQBundle\FeatureContext;

use Behat\Behat\Tester\Exception\PendingException;
use PHPUnit_Framework_Assert as Test;
use Sententiaregum\Bundle\RedisMQBundle\Entity\QueueEntity;
use Sententiaregum\Common\Behat\Context;

class RedisContext extends Context
{
    /**
     * @var \Sententiaregum\Bundle\RedisMQBundle\Service\QueueContext
     */
    private $service;

    /**
     * @var \SplObjectStorage
     */
    private $entities;

    public function __construct($databaseName, $databaseUser, $databasePassword)
    {
        parent::__construct($databaseName, $databaseUser, $databasePassword);

        $this->service = $this->container->get('sen.redis_queue.context');
    }

    /** @BeforeScenario */
    public function setUp()
    {
        $this->entities = new \SplObjectStorage();
    }

    /** @AfterScenario */
    public function purge()
    {
        $this->entities = null;

        $this->container->get('snc_redis.default')->flushdb();
    }

    /**
     * @Given I have an entity to push into the queue
     */
    public function iHaveAnEntityToPushIntoTheQueue()
    {
        $entity = new QueueEntity(['foo' => []]);
        $this->entities->attach($entity);
    }

    /**
     * @When I have pushed the entity
     */
    public function iHavePushedTheEntity()
    {
        foreach ($this->entities as $entity) {
            $this->service->push($entity);
            $this->service->enqueue();
        }
    }

    /**
     * @Then the queue should contain the entity
     */
    public function theQueueShouldContainTheEntity()
    {
        foreach ($this->entities as $entity) {
            Test::assertInstanceOf(QueueEntity::class, $entity);
            $list = iterator_to_array($this->service->getIterator());

            $contains = false;
            foreach ($list as $queueEntity) {
                Test::assertInstanceOf(QueueEntity::class, $queueEntity);

                if ($queueEntity->getValue() === $entity->getValue()) {
                    $contains = true;
                }
            }

            Test::assertTrue($contains);
        }
    }

    /**
     * @Given there are dummy entities stored in the queue
     */
    public function thereAreDummyEntitiesStoredInTheQueue()
    {
        for ($i = 0; $i < 5; $i++) {
            $queueEntity = new QueueEntity([]);
            $this->service->push($queueEntity);
        }

        $this->service->enqueue();
    }

    /**
     * @When I pop them all
     */
    public function iPopThemAll()
    {
        while ($entity = $this->service->pop()) {
            Test::assertInstanceOf(QueueEntity::class, $entity);
        }
    }

    /**
     * @Then the queue should contain no entities
     */
    public function theQueueShouldContainNoEntities()
    {
        Test::assertCount(0, $this->service->getIterator());
    }
}
 