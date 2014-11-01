<?php

namespace Sententiaregum\Common\Behat;

use AppKernel;
use Behat\Behat\Context\Context as BehatContext;
use Behat\Behat\Context\SnippetAcceptingContext;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Sententiaregum\Bundle\UserBundle\FeatureContext\Exception\IllegalDatabaseConnectionException;

require_once __DIR__ . '/../../../../app/AppKernel.php';

abstract class Context implements BehatContext, SnippetAcceptingContext
{
    protected $container;

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
