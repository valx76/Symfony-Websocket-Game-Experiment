<?php

namespace App\Shared\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class GameStorageConnectCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $host = $container->resolveEnvPlaceholders(
            $container->getParameter('game_storage_host'),
            true
        );

        $port = $container->resolveEnvPlaceholders(
            $container->getParameter('game_storage_port'),
            true
        );

        $ids = $container->findTaggedServiceIds('app.game_storage');

        foreach ($ids as $id => $data) {
            $definition = $container->getDefinition($id);
            $definition->addMethodCall('connect', [$host, $port]);
        }
    }
}