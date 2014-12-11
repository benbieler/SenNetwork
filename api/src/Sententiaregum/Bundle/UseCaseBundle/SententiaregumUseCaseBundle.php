<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\UseCaseBundle;

use Sententiaregum\Bundle\UseCaseBundle\DependencyInjection\Compiler\ContextPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SententiaregumUseCaseBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ContextPass());
        parent::build($container);
    }
}
