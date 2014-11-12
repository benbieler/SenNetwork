<?php

namespace Sententiaregum\Bundle\RedisQueueBundle\Command;

use Predis\Client;
use Sententiaregum\Bundle\RedisQueueBundle\Entity\CachedSqlResult;
use Sententiaregum\Bundle\RedisQueueBundle\Repository\CacheableRepository;
use Sententiaregum\Bundle\RedisQueueBundle\Service\RedisQueueInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RedisJob extends Command
{
    /**
     * @var RedisQueueInterface
     */
    private $queue;

    /**
     * @var Client
     */
    private $client;

    public function __construct(RedisQueueInterface $queue, Client $client)
    {
        $this->queue  = $queue;
        $this->client = $client;
    }

    protected function configure()
    {
        $this
            ->setName('sententiaregum:redis:queue-job')
            ->setDescription('Command which refreshed the redis queue');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Redis queue will be refreshed.');

        $keys = array();
        foreach ($this->client->keys(CacheableRepository::CACHE_NAMESPACE_PREFIX . '*') as $key) {
            $value = $this->client->get($key);

            $entity = CachedSqlResult::createFromJson($value);
            $this->queue->attach($entity);
            $keys[] = $key;
        }

        /** @var CachedSqlResult $entity */
        foreach (array_combine($keys, iterator_to_array($this->queue->progress())) as $query => $entity) {
            $this->client->set($query, (string) $entity);
        }

        $output->writeln('<fg=green>All queue items has been refreshed successfully</fg=green>');
        $output->writeln('Creating a backup');

        // making a security backup
        $this->client->save();

        $output->writeln('Queue finished successfullly');
    }
}
