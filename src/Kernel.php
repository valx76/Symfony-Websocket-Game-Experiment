<?php

namespace App;

use App\Game\DependencyInjection\WebsocketControllerGameServerCompilerPass;
use App\Shared\DependencyInjection\GameStorageConnectCompilerPass;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new GameStorageConnectCompilerPass());
        $container->addCompilerPass(new WebsocketControllerGameServerCompilerPass());
    }
}
