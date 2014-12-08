<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\CommonBundle\Image;

interface ImageRewriterInterface
{
    /**
     * @param string $path
     * @return boolean
     * @throws \InvalidArgumentException
     */
    public function rewriteImage($path);
}
