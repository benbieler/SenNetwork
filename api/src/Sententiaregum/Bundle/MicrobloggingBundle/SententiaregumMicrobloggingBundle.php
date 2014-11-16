<?php

namespace Sententiaregum\Bundle\MicrobloggingBundle;

use Sententiaregum\Common\Compiler\ValidatorPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SententiaregumMicrobloggingBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $configPath = $this->getPath() . '/Resources/config';
        $container->addCompilerPass(new ValidatorPass($configPath));
    }
}
