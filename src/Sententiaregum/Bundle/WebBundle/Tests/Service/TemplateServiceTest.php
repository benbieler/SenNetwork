<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\WebBundle\Tests\Service;

use Sententiaregum\Bundle\WebBundle\Service\TemplateService;
use Sententiaregum\Bundle\WebBundle\Service\Value\FormReference;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

class TemplateServiceTest extends \PHPUnit_Framework_TestCase 
{
    public function testFormMatcher()
    {
        $templating = $this->getMock(EngineInterface::class);
        $templating
            ->expects($this->any())
            ->method('exists')
            ->will($this->returnValue(true));

        $service = new TemplateService($templating);

        $service->appendFormSet([
            $formReference = new FormReference('form_alias', '@AcmeDemoBundle:Default:form.html.twig'),
            new FormReference('else_alias')
        ]);

        $reference = $service->findFormReferenceByAlias('form_alias');

        $this->assertInstanceOf(FormReference::class, $reference);
        $this->assertSame($reference, $formReference);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Form must be an instance of Sententiaregum\Bundle\WebBundle\Service\Value\FormReference
     */
    public function testFormMatcherGetsInvalidReference()
    {
        $templating = $this->getMock(EngineInterface::class);
        $templating
            ->expects($this->any())
            ->method('exists')
            ->will($this->returnValue(true));

        $service = new TemplateService($templating);

        $service->appendFormSet(['foo']);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Form with alias "test" does not exist in the container!
     */
    public function testInvalidSearch()
    {
        $templating = $this->getMock(EngineInterface::class);
        $templating
            ->expects($this->any())
            ->method('exists')
            ->will($this->returnValue(true));

        $service = new TemplateService($templating);
        $service->findFormReferenceByAlias('test');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The template "@AcmeDemoBundle:Invalid:invalid.html.twig" does not exist!
     */
    public function testInvalidFormTemplate()
    {
        $templating = $this->getMock(EngineInterface::class);
        $templating
            ->expects($this->any())
            ->method('exists')
            ->will($this->returnValue(false));

        $service = new TemplateService($templating);

        $service->appendFormSet(
            [
                new FormReference('form_alias', '@AcmeDemoBundle:Invalid:invalid.html.twig')
            ]
        );
    }
}
