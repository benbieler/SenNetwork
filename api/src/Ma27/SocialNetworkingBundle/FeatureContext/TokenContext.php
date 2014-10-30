<?php

namespace Ma27\SocialNetworkingBundle\FeatureContext;

use Behat\Gherkin\Node\TableNode;
use Ma27\SocialNetworkingBundle\FeatureContext\Abstracts\Context as AbstractContext;
use Ma27\SocialNetworkingBundle\FeatureContext\Helper\TokenHelper;
use PHPUnit_Framework_Assert as Test;

/**
 * Defines application features from the specific context.
 */
class TokenContext extends AbstractContext
{
    /**
     * @var TokenHelper
     */
    private $tokenHelper;

    /**
     * @var mixed[]
     */
    private $result;

    /**
     * @var \Ma27\SocialNetworkingBundle\Entity\User\Api\UserInterface
     */
    private $dummyUser;

    /**
     * @var string
     */
    private $token;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     *
     * @param string $databaseName
     * @param string $databaseUser
     * @param string $databasePassword
     */
    public function __construct($databaseName, $databaseUser, $databasePassword)
    {
        parent::__construct($databaseName, $databaseUser, $databasePassword);

        $this->tokenHelper = new TokenHelper(
            $this->container->get('ma27.token.controller'),
            $this->container->get('ma27.user.repository'),
            $this->container->get('ma27.util.hasher')
        );
    }

    /** @BeforeScenario */
    public function setUpScenario()
    {
        $this->dummyUser = $this->tokenHelper->createDummyUser();
    }

    /** @AfterScenario */
    public function purge()
    {
        $this->tokenHelper->flush();
    }

    /**
     * @When I send a token request with the following credentials:
     */
    public function iSendATokenRequestWithTheFollowingCredentials(TableNode $table)
    {
        $this->result = $this->tokenHelper->requestToken($table->getHash()[0]);
    }

    /**
     * @Then I should see :arg1
     */
    public function iShouldSee($arg1)
    {
        $result = $this->result;

        Test::assertArrayHasKey('errors', $result);
        Test::assertContains($arg1, $result['errors']);
    }

    /**
     * @Then I should have an api token
     */
    public function iShouldHaveAnApiToken()
    {
        $result = $this->result;

        Test::assertArrayNotHasKey('errors', $result);
        Test::assertArrayHasKey('token', $result);
        $this->token = $result['token'];
    }

    /**
     * @Then the token should be stored in the database
     */
    public function theTokenShouldBeStoredInTheDatabase()
    {
        $userId = $this->dummyUser->getId();
        /** @var \Ma27\SocialNetworkingBundle\Entity\User\Api\UserRepositoryInterface $userRepo */
        $userRepo = $this->container->get('ma27.user.repository');

        Test::assertSame($this->token, $userRepo->findApiTokenByUserId($userId));
    }
}
