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

use Sententiaregum\Bundle\UseCaseBundle\Value\UseCase;

interface ContainerInterface
{
    /**
     * @return \Sententiaregum\Bundle\UseCaseBundle\Builder\UseCaseChainInterface
     */
    public function buildContextExecutionChain();

    /**
     * @param UseCase $useCase
     * @param string $input
     * @return $this
     */
    public function pushTask(UseCase $useCase, $input);

    /**
     * @param mixed[] $parameters
     * @return mixed[]
     */
    public function run(array $parameters);
}
