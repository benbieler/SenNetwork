<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\UseCaseBundle\Container;

use Sententiaregum\Bundle\UseCaseBundle\Context\ContextInterface;
use Sententiaregum\Bundle\UseCaseBundle\Exception\ContainingUseCaseException;
use Sententiaregum\Bundle\UseCaseBundle\Exception\UseCaseNotFoundException;
use Sententiaregum\Bundle\UseCaseBundle\Value\UseCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContextContainer extends \SplObjectStorage
{
    /**
     * @param string $alias
     * @param ContextInterface $context
     * @param OptionsResolver $optionResolver
     * @return void
     * @throws ContainingUseCaseException
     */
    public function add($alias, ContextInterface $context, OptionsResolver $optionResolver)
    {
        $useCase = new UseCase($alias, $context, $optionResolver);
        if (parent::contains($useCase)) {
            throw new ContainingUseCaseException('Use case already in container!');
        }

        parent::attach($useCase);
    }

    /**
     * @param string $alias
     * @return UseCase
     * @throws UseCaseNotFoundException
     */
    public function get($alias)
    {
        /** @var UseCase $useCase */
        foreach ($this as $useCase) {
            if ($alias === $useCase->getAlias()) {
                return $useCase;
            }
        }

        throw new UseCaseNotFoundException(sprintf('Use case %s not found!', $alias));
    }

    /**
     * @return UseCase[]
     */
    public function all()
    {
        return iterator_to_array($this);
    }
}
