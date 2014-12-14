<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\CommonBundle\Behat;

use AppKernel;
use Behat\Behat\Context\Context as BehatContext;
use Behat\Behat\Context\SnippetAcceptingContext;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Sententiaregum\Bundle\CommonBundle\Exception\IllegalDatabaseConnectionException;

abstract class Context implements BehatContext, SnippetAcceptingContext
{
    /**
     * @var \Symfony\Component\DependencyInjection\Container
     */
    protected $container;

    /**
     * @param $databaseName
     * @param $databaseUser
     * @param $databasePassword
     * @throws IllegalDatabaseConnectionException
     */
    public function __construct($databaseName, $databaseUser, $databasePassword)
    {
        $kernel = new AppKernel('test', false);
        $kernel->boot();

        $this->container = $kernel->getContainer();

        $dbConnection = $this->container->get('database_connection');
        if (!$dbConnection instanceof Connection) {
            throw new IllegalDatabaseConnectionException;
        }

        $params = $dbConnection->getParams();
        $params['dbname'] = (string) $databaseName;
        $params['user'] = (string) $databaseUser;
        $params['password'] = (string) $databasePassword;

        $this->container->set('doctrine.dbal.default_connection', DriverManager::getConnection($params));
    }
}
