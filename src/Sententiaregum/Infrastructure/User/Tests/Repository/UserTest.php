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

use Sententiaregum\CoreDomain\User\Token;
use Sententiaregum\CoreDomain\User\User;
use Sententiaregum\Infrastructure\Test\RepositoryTestCase;

class UserAggregateRespositoryTest extends RepositoryTestCase
{
    public static function setUpBeforeClass()
    {
        static::setUpRepositorySet(User::class);
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

        static::$em->persist($user);
        static::$em->flush();

        $repo   = static::$repository;
        $result = $repo->findOneByName('admin');

        $this->assertSame($user, $result);
    }

    public function testFindByToken()
    {
        $user = new User(null, 'password', null);
        $user->createToken();

        $token = $user->getToken();
        $this->assertInstanceOf(Token::class, $token);

        $em = static::$em;
        $em->persist($token);
        $em->persist($user);
        $em->flush();

        $result = static::$repository->findOneByApiKey($token->getApiKey());
        $this->assertInstanceOf(User::class, $result);
        $this->assertSame($result->getToken(), $token);
    }
}
