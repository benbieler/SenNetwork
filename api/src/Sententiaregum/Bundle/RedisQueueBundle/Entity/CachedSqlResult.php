<?php

namespace Sententiaregum\Bundle\RedisQueueBundle\Entity;

use Sententiaregum\Common\Api\FromJsonInterface;
use Sententiaregum\Common\Json\ToJsonConverter;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CachedSqlResult implements FromJsonInterface
{
    /**
     * @var string
     */
    private $query;

    /**
     * @var mixed
     */
    private $result;

    /**
     * @var mixed[]
     */
    private $parameters;

    public function __construct($sqlQuery, $result, array $sqlParams = [])
    {
        $this->query = (string) $sqlQuery;
        $this->result = $result;
        $this->parameters = $sqlParams;
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
     * @return \mixed[]
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @return string
     */
    final public function __toString()
    {
        return (new ToJsonConverter())->toJson($this, ['query', 'result', 'parameters']);
    }

    /**
     * @param string $jsonString
     * @return mixed
     */
    final public static function createFromJson($jsonString)
    {
        $optionResolver = new OptionsResolver();
        $optionResolver->setRequired(['query', 'result']);
        $optionResolver->setOptional(['parameters']);
        $optionResolver->setDefaults(['parameters' => array()]);
        $optionResolver->setAllowedTypes(['query' => 'string']);

        $result = json_decode($jsonString, true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \InvalidArgumentException('Json decode failure: ' . json_last_error_msg());
        }
        $result = $optionResolver->resolve($result);

        return new static($result['query'], $result['result'], $result['parameters']);
    }
}
