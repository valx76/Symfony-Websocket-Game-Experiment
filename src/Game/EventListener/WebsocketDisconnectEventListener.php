<?php

namespace App\Game\EventListener;

use App\Game\Event\WebsocketDisconnectEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
class WebsocketDisconnectEventListener
{
    public function __invoke(WebsocketDisconnectEvent $event): void
    {
        $gameServer = $event->getGameServer();
        $connection = $event->getConnection();

        $gameServer->removePlayer($connection);
    }
}