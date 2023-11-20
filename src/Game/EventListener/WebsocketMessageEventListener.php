<?php

namespace App\Game\EventListener;

use App\Game\Event\WebsocketMessageEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
class WebsocketMessageEventListener
{
    public function __invoke(WebsocketMessageEvent $event): void
    {
        $gameServer = $event->getGameServer();
        $connection = $event->getConnection();
        $message = $event->getMessage();

        $gameServer->processMessage($connection, $message);
    }
}