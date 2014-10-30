<?php

namespace Ma27\SocialNetworkingBundle\FeatureContext;

use Ma27\SocialNetworkingBundle\FeatureContext\Abstracts\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
class RegistrationContext extends Context
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct($databaseName, $databaseUser, $databasePassword)
    {
    }
}
