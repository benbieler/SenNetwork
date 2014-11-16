<?php

namespace Sententiaregum\Common\Image;

class ImageRewriter implements ImageRewriterInterface
{
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
