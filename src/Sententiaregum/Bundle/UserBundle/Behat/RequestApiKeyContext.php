<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\UserBundle\Behat;

use Behat\Gherkin\Node\TableNode;
use PHPUnit_Framework_Assert as Test;
use Sententiaregum\Behat\AbstractContext;
use Sententiaregum\CoreDomain\User\Token;
use Sententiaregum\CoreDomain\User\User;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Functional test in order to check the request api key feature
 */
class RequestApiKeyContext extends AbstractContext
{
    /**
     * @var string
     */
    private $username;

    /**
     * @var JsonResponse
     */
    private $response;

    /** @AfterScenario */
    public function tearDown()
    {
        $this->username = null;
        $this->response = null;
    }

    /**
     * @Given these users are registered
     */
    public function theseUsersAreRegistered(TableNode $table)
    {
        $entityManager = $this->container->get('doctrine.orm.default_entity_manager');

        foreach ($table->getHash() as $row) {
            $username = $row['name'];
            $password = $row['password'];
            $email    = $row['email'];

            $user = new User($username, $password, $email);
            $entityManager->persist($user);
        }
        $entityManager->flush();
    }

    /**
     * @Given my name is :arg1
     */
    public function myNameIs($arg1)
    {
        $this->username = $arg1;
    }

    /**
     * @Given I don't have an api key
     */
    public function iDonTHaveAnApiKey()
    {
        $repo = $this->container->get('sen.user.repository');
        $user = $repo->findOneByName($this->username);

        Test::assertInstanceOf(User::class, $user);
        Test::assertNull($user->getToken());
    }

    /**
     * @When I login with the following credentials:
     */
    public function iLoginWithTheFollowingCredentials(TableNode $table)
    {
        $row    = $table->getHash()[0];
        $client = $this->container->get('test.client');

        $client->request(
            'POST',
            '/api/auth/api_key.json',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(
                [
                    'sen_user_form_auth' => [
                        'username' => $row['username'],
                        'password' => $row['password'],
                    ]
                ]
            )
        );

        $this->response = $client->getResponse();
    }

    /**
     * @Then I should have an api key
     */
    public function iShouldHaveAnApiKey()
    {
        $response = $this->response;

        $contentType = $response->headers->get('Content-Type');
        Test::assertContains('application/json', $contentType);
        Test::assertNotContains('text/html', $contentType);

        $data = json_decode($response->getContent(), true);
        Test::assertArrayHasKey('apiKey', $data);

        $key = $data['apiKey'];
        Test::assertNotNull($key);
        Test::assertInstanceOf(User::class, $user = $this->container->get('sen.user.repository')->findOneByApiKey($key));
        Test::assertSame($this->username, $user->getUsername());
    }

    /**
     * Purges the database
     */
    protected function purgeDatabase()
    {
        /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
        $entityManager = $this->container->get('doctrine.orm.default_entity_manager');

        $entityManager->createQuery(sprintf("DELETE FROM %s", User::class))->execute();
        $entityManager->createQuery(sprintf("DELETE FROM %s", Token::class))->execute();
    }
}
