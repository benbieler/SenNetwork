<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Behat;

use AppKernel;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;

/**
 * Abstract context implementation which creates the sf2 kernel
 */
abstract class AbstractContext implements Context, SnippetAcceptingContext
{
    /** @var  AppKernel */
    protected $kernel;

    /** @var  \Symfony\Component\DependencyInjection\ContainerInterface */
    protected $container;

    /** @BeforeScenario */
    public function boot()
    {
        $this->kernel = new AppKernel('test', true);
        $this->kernel->boot();

        $this->container = $this->kernel->getContainer();
    }

    /** @AfterScenario */
    public function terminate()
    {
        $this->purgeDatabase();

        $this->kernel    = null;
        $this->container = null;
    }

    /**
     * Purges the database
     */
    abstract protected function purgeDatabase();
}
