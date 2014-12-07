<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\FollowerBundle\Entity;

use Doctrine\DBAL\Connection;
use Sententiaregum\Bundle\FollowerBundle\Entity\Api\AdvancedFollowerRepositoryInterface;

class FollowerRepository implements AdvancedFollowerRepositoryInterface
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param Follower $relationShip
     * @return void
     */
    public function createRelation(Follower $relationShip)
    {
        if ($this->checkRelation($relationShip)) {
            return;
        }

        $this->connection->insert(
            'se_followers',
            ['user_id' => $relationShip->getUser(), 'follower_id' => $relationShip->getFollowing()]
        );
    }

    /**
     * @param Follower $follower
     * @return boolean
     */
    protected function checkRelation(Follower $follower)
    {
        $stmt = $this->connection->prepare("SELECT 1 FROM `se_followers` WHERE `user_id` = :user_id AND `follower_id` = :follower_id");
        $stmt->execute([':user_id' => $follower->getUser(), ':follower_id' => $follower->getFollowing()]);

        return (boolean) $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * @param integer $userId
     * @return Follower[]
     */
    public function findFollowingByUserId($userId)
    {
        $result = [];
        $followerDummy = new Follower();
        $followerDummy->setUser($userId);

        $stmt = $this->connection->prepare("SELECT `follower_id` FROM `se_followers` WHERE `user_id` = :user_id");
        $stmt->execute([':user_id' => $userId]);

        foreach ($stmt->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            $followerElement = clone $followerDummy;
            $followerElement->setFollowing($row['follower_id']);

            $result[] = $followerElement;
        }

        return $result;
    }

    /**
     * @param Follower $follower
     * @return void
     */
    public function dropRelation(Follower $follower)
    {
        $this->connection->delete(
            'se_followers',
            ['user_id' => $follower->getUser(), 'follower_id' => $follower->getFollowing()]
        );
    }

    /**
     * @param integer $userId
     * @return boolean
     */
    public function hasFollowers($userId)
    {
        $stmt = $this->connection->prepare("SELECT 1 FROM `se_followers` WHERE `user_id` = :id LIMIT 1;");
        $stmt->bindParam(':id', $userId);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    /**
     * @param integer $userId
     * @param integer $limit
     * @return integer[]
     */
    public function findFollowingByFollowingOfUser($userId, $limit = null)
    {
        $query = "
SELECT DISTINCT s.`follower_id` FROM `se_followers` s
JOIN `se_followers` r ON r.`user_id` = :user_id
WHERE s.`user_id` = r.`follower_id` AND NOT EXISTS (
  SELECT 1 FROM `se_followers` r2 WHERE r2.`user_id` = :user_id AND r2.`follower_id` = s.`follower_id`
) LIMIT " . $limit ?: 100 . ";
";

        $stmt = $this->connection->prepare($query);
        $stmt->execute([':user_id' => $userId]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @return void
     */
    public function flush()
    {
        $this->connection->exec("DELETE FROM `se_followers`;");
    }
}
