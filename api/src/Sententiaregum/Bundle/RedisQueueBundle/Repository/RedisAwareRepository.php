<?php

namespace Sententiaregum\Bundle\RedisQueueBundle\Repository;

use Doctrine\DBAL\Query\QueryBuilder;
use Predis\Client;
use Sententiaregum\Bundle\RedisQueueBundle\Entity\CachedSqlResult;
use Sententiaregum\Bundle\RedisQueueBundle\Exception\CacheHashException;

class RedisAwareRepository implements CacheableRepository
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $redisNamespace = 'sql::stmt::';

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $redisNamespace
     * @return $this
     */
    public function setRedisNamespace($redisNamespace)
    {
        $this->redisNamespace = (string) $redisNamespace;
        return $this;
    }

    /**
     * @param QueryBuilder $query
     * @return string
     */
    public function createQueryHash(QueryBuilder $query)
    {
        $raw = $query->getSQL();
        $params = $query->getParameters();

        // just create a string containing the json encoded parameters and the raw sql string
        return md5($raw . '|' . json_encode($params));
    }

    /**
     * @param string $hash
     * @return boolean
     */
    public function has($hash)
    {
        return $this->client->exists($this->createRedisQuery($hash));
    }

    /**
     * @param string $hash
     * @return CachedSqlResult
     */
    public function get($hash)
    {
        return $this->client->get($this->createRedisQuery($hash));
    }

    /**
     * @param $hash
     * @param CachedSqlResult $item
     * @return void
     */
    public function cache($hash, CachedSqlResult $item)
    {
        if ($this->has($hash)) {
            throw new CacheHashException($hash);
        }

        $this->client->set($this->createRedisQuery($hash), (string) $item);
    }

    /**
     * @param string $hash
     * @return string
     */
    private function createRedisQuery($hash)
    {
        return $this->redisNamespace . $hash;
    }
}
