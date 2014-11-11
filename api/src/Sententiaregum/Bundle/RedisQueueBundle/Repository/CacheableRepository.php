<?php
namespace Sententiaregum\Bundle\RedisQueueBundle\Repository;

use Doctrine\DBAL\Query\QueryBuilder;
use Sententiaregum\Bundle\RedisQueueBundle\Entity\CachedSqlResult;

interface CacheableRepository
{
    /**
     * @param QueryBuilder $query
     * @return string
     */
    public function createQueryHash(QueryBuilder $query);

    /**
     * @param string $hash
     * @return boolean
     */
    public function has($hash);

    /**
     * @param string $hash
     * @return CachedSqlResult
     */
    public function get($hash);

    /**
     * @param $hash
     * @param CachedSqlResult $item
     * @return void
     */
    public function cache($hash, CachedSqlResult $item);
}
