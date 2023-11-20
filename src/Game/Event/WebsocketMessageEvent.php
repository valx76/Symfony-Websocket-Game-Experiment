<?php

namespace App\Game\Event;

use App\Game\GameServer;
use Ratchet\ConnectionInterface;
use Symfony\Contracts\EventDispatcher\Event;

class WebsocketMessageEvent extends Event
{
    public function __construct(
        private readonly GameServer $gameServer,
        private readonly ConnectionInterface $connection,
        private readonly string $message
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

    public function getMessage(): string
    {
        return $this->message;
    }
}