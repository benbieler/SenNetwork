<?php
namespace Ma27\SocialNetworkingBundle\Entity\User\Api;

interface UserRepositoryInterface
{
    /**
     * @param string $name
     * @return UserInterface
     */
    public function findByName($name);

    /**
     * @param integer $userId
     * @return mixed[]
     */
    public function findRolesByUser($userId);

    /**
     * @param $userId
     * @return UserInterface
     */
    public function findById($userId);

    /**
     * @param string $token
     * @return UserInterface
     */
    public function findUserIdByApiToken($token);

    /**
     * @param string $token
     * @param integer $userId
     * @return boolean
     */
    public function storeToken($token, $userId);
}
