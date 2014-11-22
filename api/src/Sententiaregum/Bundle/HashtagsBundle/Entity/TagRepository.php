<?php

namespace Sententiaregum\Bundle\HashtagsBundle\Entity;

use Doctrine\DBAL\Connection;
use PDO;
use Sententiaregum\Bundle\HashtagsBundle\Entity\Api\TagRepositoryInterface;

class TagRepository implements TagRepositoryInterface
{
    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param Tag $tag
     */
    public function add(Tag $tag)
    {
        if (null === $this->findByName($tag->getName())) {
            $this->connection->insert('se_hashtags', ['name' => $tag->getName()]);
        }
    }

    /**
     * @param string $name
     * @return Tag
     */
    public function findByName($name)
    {
        if ((boolean) preg_match('/^#.*$/', $name)) {
            $name = substr($name, 1);
        }

        $qB = $this->connection->createQueryBuilder();
        $qB
            ->select('t.hashtag_id', 't.name')
            ->from('se_hashtags', 't')
            ->where('t.name = :tag')
            ->setParameter(':tag', $name);

        $data = $qB->execute()->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            return;
        }

        return $this->row2Entity($data);
    }

    public function flush()
    {
        $this->connection->exec("DELETE FROM `se_hashtags`;");
    }

    /**
     * @param mixed[] $row
     * @return Tag
     */
    protected function row2Entity(array $row)
    {
        $entity = new Tag();
        $entity->setId($row['hashtag_id']);
        $entity->setName($row['name']);

        return $entity;
    }
}
