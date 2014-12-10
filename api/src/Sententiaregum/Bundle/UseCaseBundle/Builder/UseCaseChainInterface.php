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

interface UseCaseChainInterface
{
    /**
     * @return \Sententiaregum\Bundle\UseCaseBundle\Builder\UseCaseChainInterface
     */
    public function end();

    /**
     * @param string $alias
     * @param string $inputClass
     * @return $this
     */
    public function appendUseCase($alias, $inputClass);
}
