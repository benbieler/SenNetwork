<?php

namespace Sententiaregum\Bundle\MicrobloggingBundle\Entity;

use Doctrine\DBAL\Connection;
use Sententiaregum\Bundle\MicrobloggingBundle\Entity\Api\MicroblogRepositoryInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MicroblogRepository implements MicroblogRepositoryInterface
{
    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var string
     */
    protected $imageUploadPath;

    /**
     * @param Connection $connection
     * @param string $imageUploadPath
     */
    public function __construct(Connection $connection, $imageUploadPath)
    {
        $this->connection      = $connection;
        $this->imageUploadPath = (string) $imageUploadPath;
    }

    /**
     * @param $content
     * @param $userId
     * @param \DateTime $creationDate
     * @param File $uploadedFile
     * @return MicroblogEntry
     */
    public function create($content, $userId, \DateTime $creationDate, File $uploadedFile = null)
    {
        $entity = new MicroblogEntry();
        $entity->setContent($content);
        $entity->setAuthorId($userId);
        $entity->setCreationDate($creationDate);
        $entity->setUploadedImage($uploadedFile);
        $entity->setImagePath($uploadedFile === null ? null : $uploadedFile->getPath());

        return $entity;
    }

    /**
     * @param MicroblogEntry $microblogEntry
     * @return MicroblogEntry
     */
    public function add(MicroblogEntry $microblogEntry)
    {
        $entry = &$microblogEntry;

        $imageTarget = $this->imageUploadPath;
        $this->connection->transactional(
            function (Connection $connection) use ($microblogEntry, $imageTarget) {
                $maxIdStmt = $connection->prepare("SELECT MAX(entry_id) FROM `se_microblogs`;");
                $maxIdStmt->execute();
                $uniqueId = $maxIdStmt->fetchColumn() + 1;
                $microblogEntry->setId($uniqueId);

                if (null !== $file = $microblogEntry->getUploadedImage()) {
                    $microblogEntry->setImagePath($this->generateImageName($uniqueId) . '.' . $file->getExtension());
                }

                $connection->insert('se_microblogs', $this->entity2Row($microblogEntry));

                // insert marked users
                # @todo fix code duplication
                $iterations = 0;
                foreach ($microblogEntry->getMarked() as $markedUser) {
                    ++$iterations;
                    if ($iterations > 75) {
                        throw new \OverflowException('Too much marked users - unable to insert');
                    }

                    $this->connection->insert('se_user_in_post', ['post_id' => $microblogEntry->getId(), 'user_name' => $markedUser]);
                }

                // insert tags
                $iterations = 0;
                /** @var \Sententiaregum\Bundle\HashtagsBundle\Entity\Tag $tag */
                foreach ($microblogEntry->getTags() as $tag) {
                    ++$iterations;
                    if ($iterations > 75) {
                        throw new \OverflowException('Too much tags - unable to insert');
                    }

                    $this->connection->insert('se_tags_in_post', ['post_id' => $microblogEntry->getId(), 'tag_name' => $tag->getName()]);
                }
            }
        );

        // if the image is a new one (this happens if the image is type of UploadedFile
        // then the image will be moved to the specified upload directory
        // if the image does not exist there already.
        if (null !== $entry->getUploadedImage() && $entry->getUploadedImage() instanceof UploadedFile) {
            $imageName = $this->generateImageName($entry->getId());
            $uploadedImage = $entry->getUploadedImage();

            $imgDestination = $imageTarget . $imageName . '.' . $uploadedImage->getExtension();
            if (!file_exists($imgDestination)) {
                $uploadedImage->move($imgDestination);
            }
        }

        return $entry;
    }

    /**
     * @return void
     */
    public function flush()
    {
        foreach (array('se_tags_in_post', 'se_user_in_post', 'se_microblogs') as $tableToDelete) {
            $this->connection->exec("DELETE FROM `" . $tableToDelete . "`;");
        }
    }

    /**
     * @param integer $entryId
     * @return boolean
     */
    public function existsById($entryId)
    {
        $qB = $this->connection->createQueryBuilder();
        $qB
            ->select('1')
            ->from('se_microblogs', 'm')
            ->where('entry_id = ' . $entryId);

        return false !== $qB->execute()->fetchColumn();
    }

    /**
     * @param MicroblogEntry $microblogEntry
     * @return mixed[]
     */
    protected function entity2Row(MicroblogEntry $microblogEntry)
    {
        return [
            'content' => $microblogEntry->getContent(),
            'creation_date' => $microblogEntry->getCreationDate()->format('Y-m-d H:i:s'),
            'author_id' => $microblogEntry->getAuthorId(),
            'entry_id' => $microblogEntry->getId(),
            'image_name' => $microblogEntry->getImagePath()
        ];
    }

    /**
     * @param integer $postId
     * @return string
     */
    protected function generateImageName($postId)
    {
        return md5((string) $postId);
    }
}
