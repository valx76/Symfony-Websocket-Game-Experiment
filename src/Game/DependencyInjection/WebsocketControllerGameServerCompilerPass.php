<?php

namespace App\Game\DependencyInjection;

use App\Game\Controller\WebsocketController;
use App\Game\GameServer;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class WebsocketControllerGameServerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $gameServerDefinition = $container->getDefinition(GameServer::class);
        $websocketControllerDefinition = $container->getDefinition(WebsocketController::class);

        $websocketControllerDefinition->addMethodCall(
            'setGameServer',
            [$gameServerDefinition]
        );
    }
}