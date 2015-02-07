<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\UserBundle\Tests\Service;

use Doctrine\ORM\EntityManagerInterface;
use Sententiaregum\Bundle\UserBundle\DTO\AuthDTO;
use Sententiaregum\Bundle\UserBundle\Event\AuthEvent;
use Sententiaregum\Bundle\UserBundle\Service\Authentication;
use Sententiaregum\Bundle\UserBundle\Tests\Fixtures\LoggerFixture;
use Sententiaregum\Domain\User\Service\ApiKeyGeneratorInterface;
use Sententiaregum\Domain\User\User;
use Sententiaregum\Domain\User\UserReadRepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class AuthenticationTest extends \PHPUnit_Framework_TestCase
{
    public function testInvalidUser()
    {
        $authDTO = new AuthDTO('invalid', 'password');
        $service = new Authentication(
            $this->getMock(UserReadRepositoryInterface::class),
            $this->getMock(ApiKeyGeneratorInterface::class),
            $this->getMock(EventDispatcherInterface::class),
            $this->getMock(EntityManagerInterface::class),
            $l = new LoggerFixture()
        );

        $service->setRequesterIp('127.0.0.1');
        $result = $service->signIn($authDTO);

        $this->assertInstanceOf(AuthEvent::class, $result);
        $this->assertTrue($result->isFailed());

        $this->assertContainsLogs(
            $l,
            'Someone tried to authenticate with invalid credentials the account ' . $authDTO->getUsername()
            . '! Reason: Invalid credentials! The ip was 127.0.0.1'
        );
    }

    public function testSuccessfulAuthentication()
    {
        $authDTO = new AuthDTO('admin', '123456');
        $repo    = $this->getMock(UserReadRepositoryInterface::class);
        $repo
            ->expects($this->any())
            ->method('findOneByName')
            ->will($this->returnValue(new User('admin', '123456', 'email@example.org')));

        $service = new Authentication(
            $repo,
            $this->getMock(ApiKeyGeneratorInterface::class),
            $this->getMock(EventDispatcherInterface::class),
            $this->getMock(EntityManagerInterface::class),
            new LoggerFixture()
        );

        $result = $service->signIn($authDTO);
        $this->assertInstanceOf(AuthEvent::class, $result);

        $this->assertFalse($result->isFailed());
        $this->assertNotNull($result->getUser()->getToken());
    }

    public function testFailedDispatch()
    {
        $authDTO = new AuthDTO('admin', '123456');
        $repo    = $this->getMock(UserReadRepositoryInterface::class);
        $repo
            ->expects($this->any())
            ->method('findOneByName')
            ->will($this->returnValue($user = new User('admin', '123456', 'email@example.org')));

        $dispatcher = $this->getMock(EventDispatcherInterface::class);
        $dispatcher
            ->expects($this->any())
            ->method('dispatch')
            ->will($this->returnValue(
                (new AuthEvent(clone $user))->fail('Any error')
            ));

        $service = new Authentication(
            $repo,
            $this->getMock(ApiKeyGeneratorInterface::class),
            $dispatcher,
            $this->getMock(EntityManagerInterface::class),
            new LoggerFixture()
        );

        $result = $service->signIn($authDTO);
        $this->assertInstanceOf(AuthEvent::class, $result);

        $this->assertTrue($result->isFailed());
        $this->assertNotNull($result->getUser());
        $this->assertNull($result->getUser()->getToken());

        $this->assertSame('Any error', $result->getFailReason());
    }

    public function testInvalidPassword()
    {
        $authDTO = new AuthDTO('admin', '123456');
        $repo    = $this->getMock(UserReadRepositoryInterface::class);
        $repo
            ->expects($this->any())
            ->method('findOneByName')
            ->will($this->returnValue(new User('admin', 'password', 'email@example.org')));

        $service = new Authentication(
            $repo,
            $this->getMock(ApiKeyGeneratorInterface::class),
            $this->getMock(EventDispatcherInterface::class),
            $this->getMock(EntityManagerInterface::class),
            new LoggerFixture()
        );

        $result = $service->signIn($authDTO);

        $this->assertNull($result->getUser());
        $this->assertTrue($result->isFailed());
        $this->assertSame('Invalid credentials', $result->getFailReason());
    }

    private function assertContainsLogs(LoggerFixture $logger, $message)
    {
        $contains = false;

        foreach ($logger->getLogs() as $element) {
            if ($element['msg'] === $message) {
                $contains = true;
                break;
            }
        }

        $this->assertTrue($contains, 'Logger message ' . $message . ' not found!');
    }
}
