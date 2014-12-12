<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace spec\Sententiaregum\Bundle\UseCaseBundle\Builder;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sententiaregum\Bundle\UseCaseBundle\Container\ContextContainer;
use Sententiaregum\Bundle\UseCaseBundle\Context\Container\ContainerInterface;
use Sententiaregum\Bundle\UseCaseBundle\Value\UseCase;

class UseCaseChainSpec extends ObjectBehavior
{
    function let(ContextContainer $contextContainer, ContainerInterface $containerInterface)
    {
        $this->beConstructedWith($contextContainer, $containerInterface);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sententiaregum\Bundle\UseCaseBundle\Builder\UseCaseChain');
    }

    function it_configures_container(ContainerInterface $containerInterface, ContextContainer $contextContainer, UseCase $useCase)
    {
        $alias = 'use case alias';

        $contextContainer->get(Argument::any())->willReturn($useCase);
        $containerInterface->pushTask(Argument::any(), Argument::any())->shouldBeCalled();

        $this->appendUseCase($alias, 'Any\Class');
        $this->end()->shouldBe($containerInterface);
    }
}
