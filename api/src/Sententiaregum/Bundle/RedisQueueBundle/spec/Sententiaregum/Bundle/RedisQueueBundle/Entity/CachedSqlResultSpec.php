<?php

namespace spec\Sententiaregum\Bundle\RedisQueueBundle\Entity;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sententiaregum\Bundle\RedisQueueBundle\Entity\CachedSqlResult;

class CachedSqlResultSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith("SELECT * FROM `foo`", []);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sententiaregum\Bundle\RedisQueueBundle\Entity\CachedSqlResult');
    }

    function it_convert_its_props_to_json()
    {
        $jsonString = $this->__toString();
        $jsonString->shouldContainProps("SELECT * FROM `foo`", []);
    }

    function it_can_be_created_by_json_string()
    {
        $json = json_encode(['query' => 'SELECT * FROM `foo`', 'result' => []]);

        $obj = $this::createFromJson($json);
        $obj->shouldBeAnInstanceOf(CachedSqlResult::class);
    }

    public function getMatchers()
    {
        return [
            'containProps' => function ($data, $query, $result) {
                $data = json_decode($data, true);

                return
                    isset($data['query']) && $data['query'] === $query
                    && isset($data['result']) && $data['result'] === $result;
            }
        ];
    }
}
