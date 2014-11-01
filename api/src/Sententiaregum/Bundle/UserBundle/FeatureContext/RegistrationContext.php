<?php

namespace Sententiaregum\Bundle\UserBundle\FeatureContext;

use Sententiaregum\Bundle\UserBundle\FeatureContext\Abstracts\Context;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Tester\Exception\PendingException;

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
        parent::__construct($databaseName, $databaseUser, $databasePassword);
    }

    /**
     * @Given there's no account in the database with name :arg1
     */
    public function thereSNoAccountInTheDatabaseWithName($arg1)
    {
        throw new PendingException();
    }

    /**
     * @When I send an account creation request with following credentials:
     */
    public function iSendAnAccountCreationRequestWithFollowingCredentials(TableNode $table)
    {
        throw new PendingException();
    }

    /**
     * @Then I should get get an success message
     */
    public function iShouldGetGetAnSuccessMessage()
    {
        throw new PendingException();
    }

    /**
     * @Then the account should be stored in database
     */
    public function theAccountShouldBeStoredInDatabase()
    {
        throw new PendingException();
    }

    /**
     * @Given there's an account with name :arg1 and email :arg2
     */
    public function thereSAnAccountWithNameAndEmail($arg1, $arg2)
    {
        throw new PendingException();
    }

    /**
     * @Then I should get message ":arg1"
     */
    public function iShouldGetMessage($arg1)
    {
        throw new PendingException();
    }
}
