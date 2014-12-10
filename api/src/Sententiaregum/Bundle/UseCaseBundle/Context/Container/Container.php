<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\UseCaseBundle\Context\Container;

use Sententiaregum\Bundle\UseCaseBundle\Builder\UseCaseChain;
use Sententiaregum\Bundle\UseCaseBundle\Container\ContextContainer;
use Sententiaregum\Bundle\UseCaseBundle\Context\DTOInterface;
use Sententiaregum\Bundle\UseCaseBundle\Exception\DTOException;
use Sententiaregum\Bundle\UseCaseBundle\Value\UseCase;

class Container implements ContainerInterface
{
    /**
     * @var ContextContainer
     */
    private $contextStack;

    /**
     * @var \Sententiaregum\Bundle\UseCaseBundle\Context\ContextInterface[]
     */
    private $taskStack = [];

    /**
     * @param ContextContainer $container
     */
    public function __construct(ContextContainer $container)
    {
        $this->contextStack = $container;
    }

    /**
     * @return \Sententiaregum\Bundle\UseCaseBundle\Builder\UseCaseChainInterface
     */
    public function buildContextExecutionChain()
    {
        return new UseCaseChain($this->contextStack, $this);
    }

    /**
     * @param UseCase $useCase
     * @param string $input
     * @return $this
     * @throws DTOException
     */
    public function pushTask(UseCase $useCase, $input)
    {
        if (!class_exists($input)) {
            throw new DTOException(sprintf('DTO input class %s does not exist', $input));
        }
        if (!$input instanceof DTOInterface) {
            throw new DTOException(sprintf('DTO class %s must implement interface %s', $input, DTOInterface::class));
        }

        $this->taskStack[] = [
            'valueObject' => $useCase,
            'inputClass' => $input
        ];

        return $this;
    }

    /**
     * @param mixed[] $parameters
     * @return mixed[]
     */
    public function run(array $parameters)
    {
        $result = [];
        foreach ($this->taskStack as $task) {
            /** @var DTOInterface $inputObject */
            $inputObject = new $task['inputClass'];
            /** @var UseCase $valueObject */
            $valueObject = $task['valueObject'];

            $inputObject->setParameters($valueObject->getDefinition()->resolve($parameters));
            $result = array_merge_recursive($result, $valueObject->getContext()->invoke($inputObject));
        }

        return $result;
    }
}
