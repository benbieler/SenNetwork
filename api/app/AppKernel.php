<?php

use Sententiaregum\Common\Kernel\Kernel;

/**
 * Sententiaregum application kernel
 */
class AppKernel extends Kernel
{
    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        $bundles = [
            // put your custom bundles here!
        ];

        return array_merge(parent::registerBundles(), $bundles);
    }
}
