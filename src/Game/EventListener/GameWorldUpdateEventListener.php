<?php

namespace App\Game\EventListener;

use App\Game\Contracts\PlayerInterface;
use App\Game\Event\GameWorldUpdateEvent;
use App\Game\Exception\GameStorageDataException;
use App\Game\Exception\GameStorageNotConnectedException;
use App\Shared\Contracts\GameStorageInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
class GameWorldUpdateEventListener
{
    public function __construct(
        private readonly GameStorageInterface $gameStorage
    )
    {
    }

    public function __invoke(GameWorldUpdateEvent $event): void
    {
        $world = $event->getWorld();

        try {
            $this->gameStorage->set(
                sprintf('world-%d', $world->getIndex()),
                json_encode($world)
            );

            array_map(
                function (PlayerInterface $player) use ($world) {
                    $player->getConnection()->send(
                        json_encode($world)
                    );
                },
                $world->getPlayers()
            );
        } catch (GameStorageDataException) {
            dump('-> Communication error with game storage');
        } catch (GameStorageNotConnectedException) {
            dump('-> Game storage not connected');
        }
    }
}