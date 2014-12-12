<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\UseCaseBundle\Fixtures;

use Sententiaregum\Bundle\UseCaseBundle\Context\ContextInterface;
use Sententiaregum\Bundle\UseCaseBundle\Context\DTOInterface;

class ExampleContext implements ContextInterface
{
    /**
     * @param DTOInterface $dto
     * @return mixed[]
     */
    public function invoke(DTOInterface $dto)
    {
        return ['bar', 'foo'];
    }
}
