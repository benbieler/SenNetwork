<?php

namespace Sententiaregum\Bundle\RedisQueueBundle\Entity;

use Sententiaregum\Common\Api\FromJsonInterface;
use Sententiaregum\Common\Json\ToJsonConverter;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CachedSqlResult implements FromJsonInterface
{
    private $query;
    private $result;

    public function __construct($sqlQuery, $result)
    {
        $this->query = (string) $sqlQuery;
        $this->result = $result;
    }

    /**
     * @return mixed
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return string
     */
    final public function __toString()
    {
        return (new ToJsonConverter())->toJson($this, ['query', 'result']);
    }

    /**
     * @param string $jsonString
     * @return mixed
     */
    public static function createFromJson($jsonString)
    {
        $optionResolver = new OptionsResolver();
        $optionResolver->setRequired(['query', 'result']);
        $optionResolver->setAllowedTypes([
            'query' => 'string'
        ]);

        $result = json_decode($jsonString, true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \InvalidArgumentException('Json decode failure: ' . json_last_error_msg());
        }
        $result = $optionResolver->resolve($result);

        return new static($result['query'], $result['result']);
    }
}
