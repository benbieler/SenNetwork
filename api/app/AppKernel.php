<?php

use Sententiaregum\Common\Kernel\Kernel;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        return array_merge(
            parent::registerBundles(),
            [
                // put your additional bundles here
            ]
        );
    }
}
