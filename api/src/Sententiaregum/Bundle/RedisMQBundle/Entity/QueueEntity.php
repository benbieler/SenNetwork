<?php

namespace Sententiaregum\Bundle\RedisMQBundle\Entity;

use Sententiaregum\Common\Api\FromJsonInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QueueEntity implements \JsonSerializable, FromJsonInterface
{
    /**
     * @var mixed
     */
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return mixed[]
     */
    public function jsonSerialize()
    {
        if (is_array($this->value)) {
            $result = $this->value;
        }
        else if ($this->value instanceof \ArrayObject) {
            $result = $this->value->getArrayCopy();
        }
        else if ($this->value instanceof \Traversable) {
            $result = iterator_to_array($this->value);
        }
        else if($this->value instanceof \JsonSerializable) {
            $result = $this->value->jsonSerialize();
        }
        else {
            $result = (array) $this->value;
        }

        return ['value' => $result];
    }

    /**
     * @param string $jsonString
     * @return mixed
     */
    public static function createFromJson($jsonString)
    {
        $decoded = json_decode($jsonString, true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \RuntimeException(
                sprintf('Deserialization of json string (%s) failed: %s', $jsonString, json_last_error_msg())
            );
        }

        $options = new OptionsResolver();
        $options->setRequired(['value']);
        return new static($options->resolve($decoded)['value']);
    }
}
