<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\FollowerBundle\FeatureContext;

use Behat\Gherkin\Node\TableNode;
use PHPUnit_Framework_Assert as Test;
use Prophecy\Prophet;
use Sententiaregum\Bundle\FollowerBundle\Entity\Follower;
use Sententiaregum\Bundle\CommonBundle\Behat\Context;
use Symfony\Component\HttpFoundation\Request;

class FollowerAdviceContext extends Context
{
    /**
     * @var \Sententiaregum\Bundle\UserBundle\Entity\UserRepository
     */
    private $userRepository;

    /**
     * @var \Sententiaregum\Bundle\UserBundle\Entity\User
     */
    private $user;

    /**
     * @var string[][]
     */
    private $followerRepository;

    /**
     * @var Prophet
     */
    private $mocker;

    /**
     * @var mixed[]
     */
    private $result;

    /**
     * @var \Sententiaregum\Bundle\FollowerBundle\Controller\FollowerController
     */
    private $followerController;

    public function __construct($databaseName, $databaseUser, $databasePassword)
    {
        parent::__construct($databaseName, $databaseUser, $databasePassword);

        $this->userRepository = $this->container->get('sen.user.repository');
        $this->followerRepository = $this->container->get('sen.follower.repository');
        $this->followerController = $this->container->get('sen.follower.controller');

        $this->mocker = new Prophet();
    }

    /** @AfterScenario */
    public function purge()
    {
        $this->followerRepository->flush();
        $this->userRepository->flush();

        $this->result = null;
    }

    /**
     * @Given there are following users:
     */
    public function thereAreFollowingUsers(TableNode $table)
    {
        foreach ($table->getHash() as $row) {
            $this->userRepository->add(
                $this->userRepository->create(
                    $row['username'],
                    null,
                    $row['email'],
                    new \DateTime()
                )
            );
        }
    }

    /**
     * @Given I'm logged in as :arg1
     */
    public function iMLoggedInAs($arg1)
    {
        $this->user = $this->userRepository->findByName($arg1);
        Test::assertNotNull($this->user);
    }

    /**
     * @Given there are the following follower relations:
     */
    public function thereAreTheFollowingFollowerRelations(TableNode $table)
    {
        foreach ($table->getHash() as $row) {
            $user = $this->userRepository->findByName($row['user'])->getId();
            $following = $this->userRepository->findByName($row['following'])->getId();
            $follower = new Follower();
            $follower->setUser($user)->setFollowing($following);

            $this->followerRepository->createRelation($follower);
        }
    }

    /**
     * @When I search for advices
     */
    public function iSearchForAdvices()
    {
        $request = $this->mocker->prophesize(Request::class);
        $request->getContent()->willReturn(json_encode(['user_id' => $this->user->getId()]));

        $response = $this->followerController->generateFollowerAdviceAction($request->reveal());
        Test::assertSame(200, $response->getStatusCode());

        $this->result = json_decode($response->getContent(), true);
        Test::assertArrayHasKey('advices', $this->result);
    }

    /**
     * @Then the advices should be in the following list:
     */
    public function theAdvicesShouldBeInTheFollowingList(TableNode $table)
    {
        $tableHash = $table->getHash();
        foreach ($this->result['advices'] as $user) {
            $check = false;
            foreach ($tableHash as $row) {
                if ($row['username'] === $user['username']) {
                    $check = true;
                    break;
                }
            }

            Test::assertTrue($check);
        }
    }

    /**
     * @Given I follow the following users:
     */
    public function iFollowTheFollowingUsers(TableNode $table)
    {
        foreach ($table->getHash() as $row) {
            $follower = new Follower();
            $follower->setUser($this->user->getId())->setFollowing($this->userRepository->findByName($row['name'])->getId());

            $this->followerRepository->createRelation($follower);
        }
    }

    /**
     * @Then I should get the following advices:
     */
    public function iShouldGetTheFollowingAdvices(TableNode $table)
    {
        $hash = $table->getHash();
        Test::assertSame(count($hash), count($this->result['advices']));
    }
}
