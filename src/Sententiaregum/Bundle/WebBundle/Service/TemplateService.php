<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\WebBundle\Service;

use Sententiaregum\Bundle\WebBundle\Service\Value\FormReference;
use Symfony\Component\Templating\EngineInterface;

/**
 * Simple template container implementation
 */
class TemplateService
{
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var FormReference[]
     */
    private $formSet = [];

    /**
     * @param EngineInterface $engine
     */
    public function __construct(EngineInterface $engine)
    {
        $this->templating = $engine;
    }

    /**
     * Appends some forms
     *
     * @param FormReference[] $elements
     *
     * @return $this
     *
     * @throws \InvalidArgumentException If the form is not an instance of FormReference
     * @throws \InvalidArgumentException If the template does not exist
     */
    public function appendFormSet(array $elements)
    {
        $templating = $this->templating;

        array_map(
            function ($element) use ($templating) {
                if (!$element instanceof FormReference) {
                    throw new \InvalidArgumentException(
                        sprintf('Form must be an instance of %s', FormReference::class)
                    );
                }

                if (!$templating->exists($element->getTemplate())) {
                    throw new \InvalidArgumentException(
                        sprintf('The template "%s" does not exist!', $element->getTemplate())
                    );
                }
            },
            $elements
        );
        $this->formSet = array_merge($elements, $this->formSet);

        return $this;
    }

    /**
     * Finds one element by its alias
     *
     * @param string $alias
     *
     * @return FormReference
     *
     * @throws \InvalidArgumentException If the form does not exist
     */
    public function findFormReferenceByAlias($alias)
    {
        foreach ($this->formSet as $reference) {
            if ($reference->getFormAlias() === $alias) {
                return $reference;
            }
        }

        throw new \InvalidArgumentException(
            sprintf('Form with alias "%s" does not exist in the container!', $alias)
        );
    }
}
