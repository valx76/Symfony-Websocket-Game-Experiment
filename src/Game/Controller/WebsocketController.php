<?php

namespace App\Game\Controller;

use App\Game\Event\WebsocketConnectEvent;
use App\Game\Event\WebsocketDisconnectEvent;
use App\Game\Event\WebsocketErrorEvent;
use App\Game\Event\WebsocketMessageEvent;
use App\Game\GameServer;
use Psr\EventDispatcher\EventDispatcherInterface;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class WebsocketController implements MessageComponentInterface
{
    private GameServer $gameServer;

    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
    )
    {
    }

    function setGameServer(GameServer $gameServer): void
    {
        $this->gameServer = $gameServer;
    }

    function onOpen(ConnectionInterface $conn): void
    {
        $this->eventDispatcher->dispatch(
            new WebsocketConnectEvent($this->gameServer, $conn)
        );
    }

    function onClose(ConnectionInterface $conn): void
    {
        $this->eventDispatcher->dispatch(
            new WebsocketDisconnectEvent($this->gameServer, $conn)
        );
    }

    function onMessage(ConnectionInterface $from, $msg): void
    {
        $this->eventDispatcher->dispatch(
            new WebsocketMessageEvent($this->gameServer, $from, $msg)
        );
    }

    function onError(ConnectionInterface $conn, \Exception $e): void
    {
        $this->eventDispatcher->dispatch(
            new WebsocketErrorEvent($this->gameServer, $conn, $e)
        );
    }
}