<?php

namespace App\Game\EventListener;

use App\Game\Event\WebsocketConnectEvent;
use App\Game\Player;
use App\Game\Vector2;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
class WebsocketConnectEventListener
{
    public function __invoke(WebsocketConnectEvent $event): void
    {
        $gameServer = $event->getGameServer();
        $connection = $event->getConnection();

        $id = random_int(1, 10); // TODO
        $position = new Vector2(random_int(0, 5), random_int(0, 5)); // TODO
        $name = 'Player'; // TODO

        $player = new Player($id, $position, $name, $connection);
        $gameServer->addPlayer($connection, $player);
    }
}