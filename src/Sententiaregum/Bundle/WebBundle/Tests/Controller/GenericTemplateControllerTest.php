<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\WebBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class GenericTemplateControllerTest extends WebTestCase
{
    public function testLayout()
    {
        $client = static::createClient();
        $client->request('GET', '/api/web/layout');
        $response = $client->getResponse();

        $this->assertHtmlResult($response);
    }

    public function testFormRenderer()
    {
        $client = static::createClient();
        $client->request('GET', '/api/web/form/form');
        $response = $client->getResponse();

        $this->assertHtmlResult($response);
    }

    protected function assertHtmlResult(Response $response)
    {
        $this->assertInstanceOf(Response::class, $response);
        $this->assertNotInstanceOf(JsonResponse::class, $response);

        $this->assertContains('text/html', $response->headers->get('Content-Type'));
        $this->assertNotContains('application/json', $response->headers->get('Content-Type'));
    }
}
