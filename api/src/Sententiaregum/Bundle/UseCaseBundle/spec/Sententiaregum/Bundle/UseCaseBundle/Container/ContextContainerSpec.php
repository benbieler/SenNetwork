<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace spec\Sententiaregum\Bundle\UseCaseBundle\Container;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sententiaregum\Bundle\UseCaseBundle\Context\ContextInterface;
use Sententiaregum\Bundle\UseCaseBundle\Exception\UseCaseNotFoundException;
use Sententiaregum\Bundle\UseCaseBundle\Value\UseCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContextContainerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Sententiaregum\Bundle\UseCaseBundle\Container\ContextContainer');
    }

    function it_stores_context_details(ContextInterface $context, OptionsResolver $resolver)
    {
        $alias = 'foo';
        $this->add($alias, $context, $resolver);

        $result = $this->get($alias);
        $result->shouldNotBe(null);
        $result->shouldBeAnInstanceOf(UseCase::class);
    }

    function it_throws_exception_if_use_case_cannot_be_found()
    {
        $this->shouldThrow(new UseCaseNotFoundException('Use case invalid not found!'))->during('get', ['invalid']);
    }

    function it_converts_itself_to_array(ContextInterface $context, OptionsResolver $resolver)
    {
        $alias1 = 'foo';
        $alias2 = 'bar';

        $this->add($alias1, $context, $resolver);
        $this->add($alias2, $context, $resolver);

        $this->all()->shouldHaveCount(2);
    }
}
