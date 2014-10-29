<?php
namespace Ma27\SocialNetworkingBundle\Entity\User\Api;

use DateTime;

interface UserRepositoryInterface
{
    /**
     * @param string $name
     * @return UserInterface
     */
    public function findByName($name);

    /**
     * @param string $email
     * @return UserInterface
     */
    public function findByEmail($email);

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

    /**
     * @param string $username
     * @param string $password
     * @param string $email
     * @param DateTime $registrationDate
     * @param DateTime $lastAction
     * @return UserInterface
     */
    public function create($username, $password, $email, DateTime $registrationDate, DateTime $lastAction = null);

    /**
     * @param UserInterface $user
     * @return integer
     */
    public function add(UserInterface $user);

    /**
     * @return void
     */
    public function flush();

    /**
     * @param integer $userId
     * @return string
     */
    public function findApiTokenByUserId($userId);
}
