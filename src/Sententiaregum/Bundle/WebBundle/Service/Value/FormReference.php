<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\WebBundle\Service\Value;

/**
 * Container which containing data of a form
 */
class FormReference
{
    /**
     * @var string
     */
    private $formAlias;

    /**
     * @var string
     */
    private $template;

    /**
     * @param string $formAlias
     * @param string $template
     */
    public function __construct($formAlias, $template = null)
    {
        $this->formAlias = (string) $formAlias;
        $this->template  = $template ?: '@SententiaregumWebBundle:GenericTemplate:form.html.twig';
    }

    /**
     * @return string
     */
    public function getFormAlias()
    {
        return $this->formAlias;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }
}
