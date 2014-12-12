<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\UseCaseBundle\Builder;

use Sententiaregum\Bundle\UseCaseBundle\Container\ContextContainer;
use Sententiaregum\Bundle\UseCaseBundle\Context\Container\ContainerInterface;

class UseCaseChain implements UseCaseChainInterface
{
    /**
     * @var ContextContainer
     */
    private $container;

    /**
     * @var ContainerInterface
     */
    private $runner;

    /**
     * @var mixed[]
     */
    private $taskStack = [];

    /**
     * @param ContextContainer $container
     * @param ContainerInterface $runner
     */
    public function __construct(ContextContainer $container, ContainerInterface $runner)
    {
        $this->container = $container;
        $this->runner = $runner;
    }

    /**
     * @return \Sententiaregum\Bundle\UseCaseBundle\Builder\UseCaseChainInterface
     */
    public function end()
    {
        $runner = &$this->runner;
        array_map(
            function ($value) use ($runner) {
                $runner->pushTask($value[0], $value[1]);
            },
            $this->taskStack
        );
        return $this->runner;
    }

    /**
     * @param string $alias
     * @param string $inputClass
     * @return $this
     */
    public function appendUseCase($alias, $inputClass)
    {
        $this->taskStack[] = [$this->container->get($alias), $inputClass];
        return $this;
    }
}
