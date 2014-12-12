<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace spec\Sententiaregum\Bundle\UseCaseBundle\Context\Container;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sententiaregum\Bundle\UseCaseBundle\Builder\UseCaseChain;
use Sententiaregum\Bundle\UseCaseBundle\Container\ContextContainer;
use Sententiaregum\Bundle\UseCaseBundle\Context\DTOInterface;
use Sententiaregum\Bundle\UseCaseBundle\Exception\DTOException;
use Sententiaregum\Bundle\UseCaseBundle\Fixtures\ExampleContext;
use Sententiaregum\Bundle\UseCaseBundle\Fixtures\ExampleDTO;
use Sententiaregum\Bundle\UseCaseBundle\Value\UseCase;
use stdClass;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContainerSpec extends ObjectBehavior
{
    function let(ContextContainer $contextContainer)
    {
        $this->beConstructedWith($contextContainer);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sententiaregum\Bundle\UseCaseBundle\Context\Container\Container');
    }

    function it_builds_execution_chain()
    {
        $this->buildContextExecutionChain()->shouldHaveType(UseCaseChain::class);
    }

    function it_throws_exception_if_dto_is_invalid(UseCase $useCase)
    {
        $this->shouldThrow(new DTOException('DTO input class invalid does not exist'))->during('pushTask', [$useCase, 'invalid']);
    }

    function it_throws_exception_if_dto_is_not_dto(UseCase $useCase)
    {
        $this->shouldThrow(new DTOException('DTO class stdClass must implement interface ' . DTOInterface::class))->during('pushTask', [$useCase, stdClass::class]);
    }

    function it_runs_task_chains()
    {
        $useCase = new UseCase('foo', new ExampleContext(), new OptionsResolver());
        $inputClass = ExampleDTO::class;

        $this->pushTask($useCase, $inputClass);
        $result = $this->run()->shouldHaveCount(2);
    }
}
