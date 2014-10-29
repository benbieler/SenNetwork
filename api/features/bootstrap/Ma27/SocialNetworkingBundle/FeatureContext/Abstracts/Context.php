<?php
namespace Ma27\SocialNetworkingBundle\FeatureContext\Abstracts;

use AppKernel;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

require_once __DIR__ . '/../../../../../../app/AppKernel.php';

abstract class Context
{
    protected $container;

    public function __construct($databaseName, $databaseUser, $databasePassword)
    {
        $kernel = new AppKernel('test', false);
        $kernel->boot();

        $this->container = $kernel->getContainer();

        $dbConnection = $this->container->get('database_connection');
        if (!$dbConnection instanceof Connection) {
            throw new \RuntimeException('Service database_connection must be an instance of ' . Connection::class);
        }

        $params = $dbConnection->getParams();
        $params['dbname'] = (string) $databaseName;
        $params['user'] = (string) $databaseUser;
        $params['password'] = (string) $databasePassword;

        $this->container->set('doctrine.dbal.default_connection', DriverManager::getConnection($params));
    }
}
