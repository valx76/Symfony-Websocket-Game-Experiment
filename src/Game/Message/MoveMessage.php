<?php

namespace App\Game\Message;

use App\Game\Contracts\MessageInterface;
use App\Game\Enum\MessageType;
use App\Game\Exception\PlayerNotFoundException;
use App\Game\Exception\PlayerNotInWorldException;
use App\Game\GameServer;
use App\Game\Vector2;
use Ratchet\ConnectionInterface;

class MoveMessage implements MessageInterface
{
    /**
     * @throws PlayerNotFoundException
     * @throws PlayerNotInWorldException
     */
    public function process(GameServer $gameServer, ConnectionInterface $connection, mixed $content): void
    {
        $player = $gameServer->getPlayerByConnection($connection);
        $world = $player->getWorld();

        if ($world === null) {
            throw new PlayerNotInWorldException();
        }

        $position = new Vector2(
            $content['positionX'],
            $content['positionY']
        );

        $world->movePlayerTo($player, $position);
    }

    public function supports(int $id): bool
    {
        return $id === MessageType::MOVE->value;
    }
}