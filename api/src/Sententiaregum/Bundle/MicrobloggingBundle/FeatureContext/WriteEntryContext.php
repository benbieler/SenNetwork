<?php

namespace Sententiaregum\Bundle\MicrobloggingBundle\FeatureContext;

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\TableNode;
use PHPUnit_Framework_Assert as Test;
use Prophecy\Prophet;
use Sententiaregum\Common\Behat\Context;
use Sententiaregum\Bundle\MicrobloggingBundle\Entity\MicroblogEntry;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

class WriteEntryContext extends Context
{
    /**
     * @var \Sententiaregum\Bundle\UserBundle\Entity\Api\UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var \Sententiaregum\Bundle\UserBundle\Entity\Api\UserInterface
     */
    private $loggedInUser;

    /**
     * @var \Sententiaregum\Bundle\MicrobloggingBundle\Entity\MicroblogRepository
     */
    private $microblogRepository;

    /**
     * @var Prophet
     */
    private $prophet;

    /**
     * @var \Sententiaregum\Bundle\HashtagsBundle\Entity\TagRepository
     */
    private $tagRepository;

    /**
     * @var \Sententiaregum\Bundle\MicrobloggingBundle\Controller\PostsController
     */
    private $postController;

    /**
     * @var \Symfony\Component\HttpFoundation\JsonResponse
     */
    private $result;

    /**
     * @var \Doctrine\DBAL\Connection
     */
    private $dbConnection;

    public function __construct($databaseName, $databaseUser, $databasePassword)
    {
        parent::__construct($databaseName, $databaseUser, $databasePassword);

        $this->userRepository = $this->container->get('sen.user.repository');
        $this->microblogRepository = $this->container->get('sen.microblog.repository');
        $this->tagRepository = $this->container->get('sen.hashtags.repository');
        $this->dbConnection = $this->container->get('database_connection');

        $this->prophet = new Prophet();
    }

    /** @AfterScenario */
    public function purge()
    {
        // clear tables
        $this->microblogRepository->flush();
        $this->tagRepository->flush();
        $this->userRepository->flush();

        // clear result
        $this->result = null;

        // remove uploaded images
        foreach (new \GlobIterator($this->container->getParameter('image_upload_path')) as $image) {
            // ignore folders and .gitkeep
            if ($image === '.gitkeep' || !is_file($image)) {
                continue;
            }

            unlink(__DIR__ . '/uploads/' . $image);
        }
    }

    /**
     * @Given there are following users:
     */
    public function thereAreFollowingUsers(TableNode $table)
    {
        foreach ($table->getHash() as $row) {
            $entity = $this->userRepository->create($row['username'], null, $row['email'], new \DateTime());
            $this->userRepository->add($entity);
        }
    }

    /**
     * @Given I'm logged in as :arg1
     */
    public function iMLoggedInAs($arg1)
    {
        $this->loggedInUser = $this->userRepository->findByName($arg1);
        Test::assertNotNull($this->loggedInUser);

        $securityContext = $this->prophet->prophesize(SecurityContextInterface::class);
        $token = $this->prophet->prophesize(TokenInterface::class);
        $token->getUser()->willReturn($this->loggedInUser);
        $securityContext->getToken()->willReturn($token->reveal());
        $this->container->set('security.context', $securityContext->reveal());

        $this->postController = $this->container->get('sen.microblog.posts.controller');
    }

    /**
     * @When I add an entry with following input:
     */
    public function iAddAnEntryWithFollowingInput(TableNode $table)
    {
        $row = $table->getHash()[0];
        $request = $this->prophet->prophesize(Request::class);
        $request->getContent()->willReturn(json_encode(array('content' => $row['content'])));
        $fileBag = $this->prophet->prophesize(FileBag::class);
        $fileBag->get('appended-image')->willReturn(new File(__DIR__ . '/Fixtures/images/' . $row['image']));

        // quickfix, prophecy cannot mock properties
        $request->files = $fileBag;

        $this->result = $this->postController->createAction($request->reveal());
    }

    /**
     * @Then the entry should be stored in the database
     */
    public function theEntryShouldBeStoredInTheDatabase()
    {
        $response = $this->result;
        $data = json_decode($response->getContent(), true);

        Test::assertArrayHasKey('entry_id', $data);
        Test::assertTrue($this->microblogRepository->existsById($data['entry_id']));
    }

    /**
     * @Then the following tags should be recognized:
     */
    public function theFollowingTagsShouldBeRecognized(TableNode $table)
    {
        $data = $this->result->getContent();
        $postId = json_decode($data, true)['entry_id'];

        foreach ($table->getHash() as $row) {
            $tagName = $row['name'];

            $stmt = $this->dbConnection->prepare(
                "SELECT 1 FROM `se_tags_in_post` WHERE `tag_name` = :name AND `post_id` = :id"
            );
            $stmt->execute([':name' => $tagName, ':id' => $postId]);

            Test::assertNotFalse($stmt->fetch());
        }
    }

    /**
     * @Then the following users should be marked:
     */
    public function theFollowingUsersShouldBeMarked(TableNode $table)
    {
        $data = $this->result->getContent();
        $postId = json_decode($data, true)['entry_id'];

        foreach ($table->getHash() as $row) {
            $tagName = $row['name'];

            $stmt = $this->dbConnection->prepare(
                "SELECT 1 FROM `se_user_in_post` WHERE `user_name` = :name AND `post_id` = :id"
            );
            $stmt->execute([':name' => $tagName, ':id' => $postId]);

            Test::assertNotFalse($stmt->fetch());
        }
    }

    /**
     * @Then the entry should not be stored in the database
     */
    public function theEntryShouldNotBeStoredInTheDatabase()
    {
        $query = $this->dbConnection->prepare("SELECT `entry_id` FROM `se_microblogs`");
        $query->execute();
        Test::assertFalse($query->fetchColumn());
    }

    /**
     * @Then I should see :arg1
     */
    public function iShouldSee($arg1)
    {
        $response = $this->result;
        $data = json_decode($response->getContent(), true);

        Test::assertArrayHasKey('errors', $data);
        Test::assertContains($arg1, $data['errors']);
    }
}
