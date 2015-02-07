<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\UserBundle\Tests\Repository;

use Sententiaregum\Domain\User\Role;
use Sententiaregum\Domain\User\Service\ApiKeyGenerator;
use Sententiaregum\Domain\User\Token;
use Sententiaregum\Domain\User\User;
use Sententiaregum\Bundle\UserBundle\Test\RepositoryTestCase;

class UserRepositoryTest extends RepositoryTestCase
{
    /**
     * @var \Sententiaregum\Bundle\UserBundle\Repository\UserRepository
     */
    protected static $repository;

    public static function setUpBeforeClass()
    {
        static::setUpRepositorySet('SEN_User:User');
    }

    protected function tearDown()
    {
        // delete manually using dbal since it seems as is not possible to delete all entities with their relations
        $connection = static::$entityManager->getConnection();

        $connection->exec("DELETE FROM `SEN_UserToRole`");
        $connection->exec("DELETE FROM SEN_Role");
        $connection->exec("DELETE FROM SEN_User");
    }

    public function testFindByName()
    {
        $user = new User('admin', 'password', 'admin@example.org');

        static::$entityManager->persist($user);
        static::$entityManager->flush();

        $repo   = static::$repository;
        $result = $repo->findOneByName('admin');

        $this->assertSame($user, $result);
    }

    public function testFindByToken()
    {
        $user = new User(null, 'password', 'email@example.org');
        $user->createToken(new ApiKeyGenerator());

        $token = $user->getToken();
        $this->assertInstanceOf(Token::class, $token);

        $repo = static::$repository;
        $repo->add($user);
        static::$entityManager->flush();

        $result = static::$repository->findOneByApiKey($token->getApiKey());
        $this->assertInstanceOf(User::class, $result);
        $this->assertSame($result->getToken(), $token);
    }

    public function testPersistAndUpdate()
    {
        $user = new User('test', 'password', 'email@example.org');
        $user->addRole($r = new Role('ROLE_USER'));

        $repo = static::$repository;
        $repo->add($user);

        static::$entityManager->flush();

        $persistedUser = $repo->findOneByName($user->getUsername());
        $this->assertInstanceOf(User::class, $persistedUser);
        $this->assertCount(1, $persistedUser->getRoles());
        $this->assertSame($user, $persistedUser);
        $this->assertSame($user->getRoles(), $persistedUser->getRoles());
        $this->assertSame($user->getRoles()[0]->getRole(), Role::USER);
        $this->assertCount(0, $user->getFollowers());

        $newUser       = new User('follower', 'password', 'follower@example.org');
        $persistedUser->addFollower($newUser);
        static::$entityManager->persist($newUser);
        static::$entityManager->flush();

        $persistedUser->removeRole($user->getRoles()[0]);
        $persistedUser->addRole(new Role('ROLE_ADMIN'));
        $persistedUser->createToken(new ApiKeyGenerator());

        $repo->update($persistedUser);
        static::$entityManager->flush();

        $updatedUser = $repo->findOneByName($user->getUsername());
        $this->assertInstanceOf(User::class, $persistedUser);
        $this->assertCount(1, $updatedUser->getFollowers());
        $this->assertCount(1, $updatedUser->getRoles());
        $role = $updatedUser->getRoles()[1];

        $this->assertSame(Role::ADMIN, $role->getRole());
    }

    public function testDeleteUser()
    {
        $user = new User('test', 'password', 'email@example.org');
        $user->addRole(new Role(Role::USER));
        $user->createToken(new ApiKeyGenerator());

        $repo = static::$repository;
        $repo->add($user);

        static::$entityManager->flush();

        $this->assertNotNull($u = $repo->findOneByName($user->getUsername()));
        $roleId = $u->getRoles()[0];

        $repo->delete($user);
        static::$entityManager->flush();

        $this->assertNull($repo->findOneByName($user->getUsername()));

        $role = static::$entityManager->find('SEN_User:Role', $roleId);
        $this->assertNotNull($role);
    }
}
