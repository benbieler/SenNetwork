<?php

namespace Sententiaregum\Bundle\FollowerBundle\FeatureContext;

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\TableNode;
use PHPUnit_Framework_Assert as Test;
use Prophecy\Prophet;
use Sententiaregum\Bundle\FollowerBundle\Entity\Follower;
use Sententiaregum\Common\Behat\Context;
use Symfony\Component\HttpFoundation\Request;

class FollowerCrudContext extends Context
{
    /**
     * @var \Sententiaregum\Bundle\UserBundle\Entity\UserRepository
     */
    private $userRepository;

    /**
     * @var \Sententiaregum\Bundle\FollowerBundle\Entity\FollowerRepository
     */
    private $followerRepository;

    /**
     * @var \Sententiaregum\Bundle\FollowerBundle\Controller\FollowerController
     */
    private $followerController;

    /**
     * @var \Sententiaregum\Bundle\UserBundle\Entity\User
     */
    private $user;

    /**
     * @var \Prophecy\Prophet
     */
    private $prophet;

    /**
     * @var mixed
     */
    private $result;

    public function __construct($databaseName, $databaseUser, $databasePassword)
    {
        parent::__construct($databaseName, $databaseUser, $databasePassword);

        $this->userRepository = $this->container->get('sen.user.repository');
        $this->followerRepository = $this->container->get('sen.follower.repository');
        $this->followerController = $this->container->get('sen.follower.controller');
        $this->prophet = new Prophet();
    }

    /** @AfterScenario */
    public function purge()
    {
        $this->followerRepository->flush();
        $this->userRepository->flush();

        $this->user = null;
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
     * @Given I don't follow :arg1
     */
    public function iDonTFollow($arg1)
    {
        $userId = $this->userRepository->findByName($arg1)->getId();
        Test::assertNotContains($userId, $this->followerRepository->findFollowingByUserId($this->user->getId()));
    }

    /**
     * @When I create a follower relation with :arg1
     */
    public function iCreateAFollowerRelationWith($arg1)
    {
        $request = $this->prophet->prophesize(Request::class);

        $myId = $this->user->getId();
        $followingId = $this->userRepository->findByName($arg1)->getId();
        $request->getContent()->willReturn(json_encode(['user_id' => $myId, 'following_id' => $followingId]));

        Test::assertSame(200, $this->followerController->createAction($request->reveal())->getStatusCode());
    }

    /**
     * @Then I should follow :arg1
     */
    public function iShouldFollow($arg1)
    {
        $userId = $this->userRepository->findByName($arg1)->getId();

        $contains = false;
        foreach ($this->followerRepository->findFollowingByUserId($this->user->getId()) as $relation) {
            if ($userId === $relation->getFollowing() && $this->user->getId() === $relation->getUser()) {
                $contains = true;
                break;
            }
        }

        Test::assertTrue($contains);
    }

    /**
     * @Given I follow the following users:
     */
    public function iFollowTheFollowingUsers(TableNode $table)
    {
        foreach ($table->getHash() as $row) {
            $entity = new Follower();
            $entity->setUser($this->user->getId());
            $entity->setFollowing($this->userRepository->findByName($row['name'])->getId());

            $this->followerRepository->createRelation($entity);
        }
    }

    /**
     * @When I list all followers
     */
    public function iListAllFollowers()
    {
        $request = $this->prophet->prophesize(Request::class);
        $request->getContent()->willReturn(json_encode(['user_id' => $this->user->getId()]));

        $this->result = $this->followerController->indexAction($request->reveal());
        Test::assertNotNull($this->result);
    }

    /**
     * @Then I should see the users :arg1
     */
    public function iShouldSeeTheUsers($arg1)
    {
        $content = json_decode($this->result->getContent(), true)['followers'];
        foreach (explode(', ', $arg1) as $name) {
            Test::assertContains($this->userRepository->findByName($name)->getId(), $content);
        }
    }

    /**
     * @When I drop the relation with user :arg1
     */
    public function iDropTheRelationWithUser($arg1)
    {
        $request = $this->prophet->prophesize(Request::class);
        $request->getContent()->willReturn(
            json_encode(['user_id' => $this->user->getId(), 'following_id' => $this->userRepository->findByName($arg1)->getId()])
        );

        Test::assertSame(200, $this->followerController->removeAction($request->reveal())->getStatusCode());
    }

    /**
     * @Then I should no longer follow :arg1
     */
    public function iShouldNoLongerFollow($arg1)
    {
        $this->iDonTFollow($arg1);
    }
}
