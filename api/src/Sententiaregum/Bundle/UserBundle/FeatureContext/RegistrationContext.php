<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\UserBundle\FeatureContext;

use PHPUnit_Framework_Assert as Test;
use Sententiaregum\Common\Behat\Context;
use Behat\Gherkin\Node\TableNode;
use Sententiaregum\Bundle\UserBundle\FeatureContext\Helper\RegistrationHelper;
use Sententiaregum\Bundle\UserBundle\Entity\Api\UserInterface;

/**
 * Defines application features from the specific context.
 */
class RegistrationContext extends Context
{
    /**
     * @var RegistrationHelper
     */
    private $registrationHelper;

    /**
     * @var \Sententiaregum\Bundle\UserBundle\Entity\Api\UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var mixed
     */
    private $result;

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

        $this->userRepository = $this->container->get('sen.user.repository');
        $this->registrationHelper = new RegistrationHelper(
            $this->userRepository, $this->container->get('sen.account.controller')
        );
    }

    /** @BeforeScenario */
    public function setUpScenario()
    {
    }

    /** @AfterScenario */
    public function purge()
    {
        $this->registrationHelper->flush();
    }

    /**
     * @Given there's no account in the database with name :arg1
     */
    public function thereSNoAccountInTheDatabaseWithName($arg1)
    {
        Test::assertNull($this->userRepository->findByName($arg1));
    }

    /**
     * @When I send an account creation request with following credentials:
     */
    public function iSendAnAccountCreationRequestWithFollowingCredentials(TableNode $table)
    {
        $data = $table->getHash()[0];
        $this->result = $this->registrationHelper->createAccount($data['username'], $data['password'], $data['email'], $data['realname']);
    }

    /**
     * @Then I should get get an success message
     */
    public function iShouldGetGetAnSuccessMessage()
    {
        Test::assertArrayHasKey('username', $this->result);
        Test::assertArrayNotHasKey('errors', $this->result);
    }

    /**
     * @Then the account should be stored in database
     */
    public function theAccountShouldBeStoredInDatabase()
    {
        Test::assertInstanceOf(UserInterface::class, $user = $this->userRepository->findByName($this->result['username']));
        Test::assertContains('ROLE_USER', $user->getRoles());
    }

    /**
     * @Given there's an account with name :arg1 and email :arg2
     */
    public function thereSAnAccountWithNameAndEmail($arg1, $arg2)
    {
        $user = $this->userRepository->create($arg1, '123456', $arg2, new \DateTime());
        $this->userRepository->add($user);
    }

    /**
     * @Then I should get message :arg1 for property :arg2
     */
    public function iShouldGetMessageForProperty($arg1, $arg2)
    {
        $errors = $this->result['errors'];

        Test::assertArrayHasKey($arg2, $errors);
        Test::assertContains($arg1, $errors[$arg2]);
    }

    /**
     * @Then I should get message :arg1
     */
    public function iShouldGetMessage($arg1)
    {
        $hasMsg = false;
        foreach ($this->result['errors'] as $message) {
            if (in_array($arg1, $message)) {
                $hasMsg = true;
                break;
            }
        }

        Test::assertTrue($hasMsg);
    }
}
