<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Infratstructure\User\Tests\Repository;

use Sententiaregum\CoreDomain\User\Service\ApiKeyGenerator;
use Sententiaregum\CoreDomain\User\Token;
use Sententiaregum\CoreDomain\User\User;
use Sententiaregum\Infrastructure\Test\RepositoryTestCase;

class UserTest extends RepositoryTestCase
{
    public static function setUpBeforeClass()
    {
        static::setUpRepositorySet('SEN_User:User');
    }

    protected function tearDown()
    {
        $this->purgeEntities(
            [
                User::class,
                Token::class
            ]
        );
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

        $entityManager = static::$entityManager;
        $entityManager->persist($token);
        $entityManager->persist($user);
        $entityManager->flush();

        $result = static::$repository->findOneByApiKey($token->getApiKey());
        $this->assertInstanceOf(User::class, $result);
        $this->assertSame($result->getToken(), $token);
    }
}
