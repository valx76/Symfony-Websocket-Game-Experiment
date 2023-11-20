<?php

namespace App\Game\Event;

use App\Game\GameServer;
use Ratchet\ConnectionInterface;
use Symfony\Contracts\EventDispatcher\Event;

class WebsocketDisconnectEvent extends Event
{
    public function __construct(
        private readonly GameServer $gameServer,
        private readonly ConnectionInterface $connection
    )
    {
    }

    public function getGameServer(): GameServer
    {
        return $this->gameServer;
    }

    public function getConnection(): ConnectionInterface
    {
        return $this->connection;
    }
}