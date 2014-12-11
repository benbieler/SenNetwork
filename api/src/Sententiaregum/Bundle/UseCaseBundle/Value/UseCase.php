<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\UseCaseBundle\Value;

use Sententiaregum\Bundle\UseCaseBundle\Context\ContextInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UseCase
{
    /**
     * @var string
     */
    private $alias;

    /**
     * @var ContextInterface
     */
    private $context;

    /**
     * @var OptionsResolver
     */
    private $definition;

    /**
     * @param string $alias
     * @param ContextInterface $context
     * @param OptionsResolver $optionsResolver
     */
    public function __construct($alias, ContextInterface $context, OptionsResolver $optionsResolver)
    {
        $this->alias = (string) $alias;
        $this->context = $context;
        $this->definition = $optionsResolver;
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @return ContextInterface
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @return OptionsResolver
     */
    public function getDefinition()
    {
        return $this->definition;
    }
}
