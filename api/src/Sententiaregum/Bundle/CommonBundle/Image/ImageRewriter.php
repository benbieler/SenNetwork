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

class ImageRewriter implements ImageRewriterInterface
{
    /**
     * @param string $path
     * @return boolean
     * @throws \InvalidArgumentException
     */
    public function rewriteImage($path)
    {
        if (!file_exists($path)) {
            throw new \InvalidArgumentException(sprintf('Path %s does not exist!', (string) $path));
        }

        $image = new \Imagick($path);
        $image->cropImage($image->getImageWidth(), $image->getImageHeight(), 0, 0);
        $image->writeImage($path);
        $image->destroy();

        return true;
    }
}
