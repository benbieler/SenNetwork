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

        $service       = new TemplateService($templating);
        $formReference = new FormReference('form_alias', '@AcmeDemoBundle:Default:form.html.twig');

        $service->appendFormSet([
            ['form' => 'form_alias', 'template' => '@AcmeDemoBundle:Default:form.html.twig']
        ]);

        $reference = $service->findFormReferenceByAlias('form_alias');

        $this->assertInstanceOf(FormReference::class, $reference);
        $this->assertSame($reference->getTemplate(), $formReference->getTemplate());
        $this->assertSame($reference->getFormAlias(), $formReference->getFormAlias());
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
                ['form' => 'form_alias', 'template' => '@AcmeDemoBundle:Invalid:invalid.html.twig']
            ]
        );
    }
}
