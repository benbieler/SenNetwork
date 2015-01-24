<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bridge\User\Tests\Service;

use Sententiaregum\Bridge\User\Service\ApiAuthenticationWorkflow;
use Sententiaregum\Bridge\User\Tests\Fixtures\LoggerFixture;
use Sententiaregum\CoreDomain\User\DTO\AuthDTO;
use Sententiaregum\CoreDomain\User\Event\AuthEvent;
use Sententiaregum\CoreDomain\User\Service\Auth;
use Sententiaregum\CoreDomain\User\User;
use Sententiaregum\CoreDomain\User\UserAggregateRepositoryInterface;

class ApiAuthenticationWorkflowTest extends \PHPUnit_Framework_TestCase
{
    public function testFailedAuthentication()
    {
        $credentials = new AuthDTO('admin', '123456');
        $resultEvent = (new AuthEvent())->fail('Invalid credentials!');

        $authDomainService = $this->getMockBuilder(Auth::class)->disableOriginalConstructor()->getMock();
        $authDomainService
            ->expects($this->any())
            ->method('authenticateUser')
            ->with($credentials)
            ->will($this->returnValue($resultEvent));

        $logger = new LoggerFixture();

        $authWorkFlow = new ApiAuthenticationWorkflow($authDomainService, $logger, $this->getMock(UserAggregateRepositoryInterface::class));
        $authWorkFlow->setRequesterIp('127.0.0.1');
        $event        = $authWorkFlow->authenticate($credentials);

        $this->assertSame($resultEvent, $event);
        $this->assertNull($resultEvent->getUser());
        $this->assertTrue(
            $this->containsLogs(
                $logger,
                'Someone tried to authenticate with invalid credentials the account with username admin! Reason: '
                . 'Invalid credentials!. The ip was: 127.0.0.1'
            )
        );
    }

    public function testSuccessfulAuthentication()
    {
        $credentials = new AuthDTO('admin', '123456');
        $event       = new AuthEvent(new User());

        $authDomainService = $this->getMockBuilder(Auth::class)->disableOriginalConstructor()->getMock();
        $authDomainService
            ->expects($this->any())
            ->method('authenticateUser')
            ->with($credentials)
            ->will($this->returnValue($event));

        $logger = new LoggerFixture();

        $authWorkFlow = new ApiAuthenticationWorkflow($authDomainService, $logger, $this->getMock(UserAggregateRepositoryInterface::class));
        $authWorkFlow->setRequesterIp('127.0.0.1');
        $event        = $authWorkFlow->authenticate($credentials);

        $this->assertFalse($event->isFailed());
        $this->assertInstanceOf(User::class, $event->getUser());
        $this->assertNotNull($event->getUser()->getToken());
    }

    private function containsLogs(LoggerFixture $logger, $message)
    {
        foreach ($logger->getLogs() as $element) {
            if ($element['msg'] === $message) {
                return true;
            }
        }
    }
}
