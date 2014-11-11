<?php

namespace Sententiaregum\Bundle\RedisQueueBundle\Exception;

class CacheHashException extends \LogicException
{
    /**
     * @var string
     */
    protected $hash;

    public function __construct($hash)
    {
        $this->hash = (string) $hash;

        parent::__construct(
            sprintf('Hash %s already cached!', $this->hash)
        );
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }
}
 