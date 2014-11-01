<?php

namespace Sententiaregum\Bundle\UserBundle\Entity;

use DateTime;
use Doctrine\DBAL\Connection;
use Sententiaregum\Bundle\UserBundle\Entity\Api\UserRepositoryInterface;
use Sententiaregum\Bundle\UserBundle\Entity\Api\UserInterface;
use PDO;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @var Connection
     */
    protected $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param string $name
     * @return UserInterface
     */
    public function findByName($name)
    {
        return $this->findBy(['username' => (string) $name]);
    }

    /**
     * @param $userId
     * @return UserInterface
     */
    public function findById($userId)
    {
        return $this->findBy(['user_id' => (integer) $userId]);
    }

    /**
     * @param string $email
     * @return UserInterface
     */
    public function findByEmail($email)
    {
        return $this->findBy(['email' => (string) $email]);
    }

    /**
     * @param string $token
     * @return UserInterface
     */
    public function findUserIdByApiToken($token)
    {
        $qB = $this->connection->createQueryBuilder()
            ->select('t.user_id')
            ->from('se_user_token', 't')
            ->where('token = :token')
            ->setParameter(':token', $token);

        return $qB->execute()->fetchColumn();
    }

    /**
     * @param mixed[] $predicates
     * @return UserInterface
     */
    protected function findBy(array $predicates)
    {
        $qB = $this->connection->createQueryBuilder()
            ->select(
                'u.user_id',
                'u.username',
                'u.realname',
                'u.password',
                'u.email',
                'u.locked',
                'u.registrationDate',
                'u.lastAction'
            )->from('se_users', 'u');

        foreach ($predicates as $columnName => $expected) {
            $qB->where(sprintf('%s = :param_' . $columnName, $columnName));
            $qB->setParameter(':param_' . $columnName, $expected);
        }

        $data = $qB->execute()->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            return;
        }

        $user = $this->convertRowToModel($data);
        $user->setRoles($this->findRolesByUser($user->getId()));

        return $user;
    }

    /**
     * @param integer $userId
     * @return mixed[]
     */
    public function findRolesByUser($userId)
    {
        $qB = $this->connection->createQueryBuilder()
            ->select('r.role_alias')
            ->from('se_user_role', 'r')
            ->where('user_id = :id')
            ->setParameter(':id', $userId);

        $result = $qB->execute()->fetch(PDO::FETCH_ASSOC);
        if (!$result) {
            return [];
        }

        return array_values($result);
    }

    /**
     * @param mixed[] $row
     * @return User
     */
    protected function convertRowToModel(array $row)
    {
        $model = new User();

        $model->setId($row['user_id']);
        $model->setUsername($row['username']);
        $model->setPassword($row['password']);
        $model->setRealName($row['realname']);
        $model->setEmail($row['email']);
        $model->setLocked($row['locked']);
        $model->setRegistrationDate(new DateTime($row['registrationDate']));
        $model->setLastAction(new DateTime($row['lastAction']));

        return $model;
    }

    /**
     * @param UserInterface $model
     * @return mixed[]
     */
    protected function convertModelToRow(UserInterface $model)
    {
        return [
            'user_id' => $model->getId(),
            'username' => $model->getUsername(),
            'password' => $model->getPassword(),
            'realname' => $model->getRealName(),
            'email' => $model->getEmail(),
            'locked' => !$model->isAccountNonLocked(),
            'registrationDate' => $model->getRegistrationDate()->format('Y-m-d H:i:s'),
            'lastAction' => $model->getLastAction()->format('Y-m-d H:i:s')
        ];
    }

    /**
     * @param string $token
     * @param integer $userId
     * @return boolean
     */
    public function storeToken($token, $userId)
    {
        try {
            $this->connection->insert('se_user_token', ['user_id' => $userId, 'token' => $token]);

            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * @param string $username
     * @param string $password
     * @param string $email
     * @param DateTime $registrationDate
     * @param DateTime $lastAction
     * @param boolean $locked
     * @return UserInterface
     */
    public function create($username, $password, $email, DateTime $registrationDate, DateTime $lastAction = null, $locked = false)
    {
        $user = new User();
        $user
            ->setUsername($username)
            ->setPassword($password)
            ->setEmail($email)
            ->setRegistrationDate($registrationDate)
            ->setLocked($locked);

        if (null === $lastAction) {
            $lastAction = new DateTime();
        }
        $user->setLastAction($lastAction);

        // set an empty string as realname to avoid problems with the database
        $user->setRealName('');

        return $user;
    }

    /**
     * @param UserInterface $user
     * @return integer
     */
    public function add(UserInterface $user)
    {
        return $this->connection->insert('se_users', $this->convertModelToRow($user));
    }

    /**
     * @return void
     */
    public function flush()
    {
        foreach (['se_user_token', 'se_user_role', 'se_users'] as $table) {
            $this->connection->exec("DELETE FROM `" . $table . "`");
        }
    }

    /**
     * @param integer $userId
     * @return string
     */
    public function findApiTokenByUserId($userId)
    {
        $qB = $this->connection->createQueryBuilder()
            ->select('t.token')
            ->from('se_user_token', 't')
            ->where('user_id = :id')
            ->setParameter(':id', $userId);

        return $qB->execute()->fetchColumn();
    }
}
