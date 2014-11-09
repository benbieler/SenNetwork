<?php

namespace spec\Sententiaregum\Common\Json;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use stdClass;

class ToJsonConverterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Sententiaregum\Common\Json\ToJsonConverter');
    }

    function it_converts_props_to_json()
    {
        $stub = new \stdClass();
        $stub->foo = 'bar';

        $jsonString = $this->toJson($stub, ['foo']);
        $jsonString->shouldContainEncodedProperties('foo', 'bar');
    }

    public function getMatchers()
    {
        return [
            'containEncodedProperties' => function ($json, $prop, $expected) {
                $decoded = json_decode($json, true);

                return isset($decoded[$prop]) && $decoded[$prop] === $expected;
            }
        ];
    }
}
