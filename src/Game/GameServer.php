<?php

namespace App\Game;

use App\Game\Contracts\PlayerInterface;
use App\Game\Contracts\WorldInterface;
use App\Game\Event\GameWorldUpdateEvent;
use App\Game\Exception\IncorrectMessageFormatException;
use App\Game\Exception\MapFileFormatException;
use App\Game\Exception\MapFileUnreadableException;
use App\Game\Exception\MessageNotFoundException;
use App\Game\Exception\NoWorldAvailableException;
use App\Game\Exception\PlayerAlreadyInWorldException;
use App\Game\Exception\PlayerNotFoundException;
use App\Game\Exception\PlayerNotInWorldException;
use App\Game\Exception\WorldFullException;
use App\Game\Service\MessageManager;
use App\Game\Util\WorldUtil;
use Psr\EventDispatcher\EventDispatcherInterface;
use Ratchet\ConnectionInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class GameServer
{
    /**
     * @var WorldInterface[] $worlds
     */
    private array $worlds;

    /**
     * @var \SplObjectStorage<ConnectionInterface, PlayerInterface> $players
     */
    private \SplObjectStorage $players;

    public function __construct(
        #[Autowire('%kernel.project_dir%/public/')]
        string $publicPath,

        #[Autowire('%env(default::int:APP_WORLDS_COUNT)%')]
        ?int $worldsCount,

        #[Autowire('%env(default::int:APP_MAX_PLAYERS_PER_WORLD)%')]
        ?int $maxPlayersPerWorld,

        #[Autowire('%env(default::string:APP_GAME_STORAGE_HOST)%')]
        ?string $gameStorageHost,

        #[Autowire('%env(default::int:APP_GAME_STORAGE_PORT)%')]
        ?int $gameStoragePort,

        private readonly MessageManager $messageManager,
        private readonly EventDispatcherInterface $eventDispatcher,
    )
    {
        if ($worldsCount === null) {
            throw new \RuntimeException('APP_WORLDS_COUNT needs to be set and higher than 0!');
        }

        if ($maxPlayersPerWorld === null) {
            throw new \RuntimeException('APP_MAX_PLAYERS_PER_WORLD needs to be set and higher than 0!');
        }

        if ($gameStorageHost === null || $gameStoragePort === null) {
            throw new \RuntimeException('GAME_STORAGE_HOST / GAME_STORAGE_PORT need to be set!');
        }

        $this->worlds = [];
        $this->players = new \SplObjectStorage();

        $mapFilePath = sprintf('%s/maps/map1.txt', $publicPath);

        for ($worldIndex = 0; $worldIndex < $worldsCount; $worldIndex++) {
            try {
                $map = new Map($mapFilePath);
                $this->worlds[$worldIndex] = new World($worldIndex, $maxPlayersPerWorld, $map);
            } catch (MapFileFormatException) {
                dump('-> Wrong map file format');
            } catch (MapFileUnreadableException) {
                dump('-> Map file unreadable or not found');
            }
        }
    }

    public function addPlayer(ConnectionInterface $connection, PlayerInterface $player): void
    {
        dump('GameServer::addPlayer');

        $this->players->attach($connection, $player);

        try {
            // TODO - Also check that the player is not in any world first!

            $world = WorldUtil::findFirstWorldAcceptingPlayers($this->worlds);
            $world->addPlayer($player);
            $player->setWorld($world);

            $this->triggerWorldUpdateEvent($world);
        } catch (NoWorldAvailableException) {
            dump('-> All worlds are full');
        } catch (PlayerAlreadyInWorldException) {
            dump('-> The player is already in this world');
        } catch (WorldFullException) {
            // INFO - Should not happen since the check is done earlier
            dump('-> The world is already full');
        }
    }

    public function removePlayer(ConnectionInterface $connection): void
    {
        dump('GameServer::removePlayer');

        try {
            $player = $this->players->offsetGet($connection);
        } catch (\UnexpectedValueException) {
            dump('-> Player was not saved (nothing to remove)');
            return;
        }

        $this->players->detach($connection);

        $world = $player->getWorld();
        $world?->removePlayer($player);
        $player->setWorld(null);

        if ($world !== null) {
            $this->triggerWorldUpdateEvent($world);
        }
    }

    public function processMessage(ConnectionInterface $connection, string $jsonMessageContent): void
    {
        try {
            $this->messageManager->processMessage($this, $connection, $jsonMessageContent);

            $player = $this->getPlayerByConnection($connection);
            $world = $player->getWorld();

            if ($world === null) {
                throw new PlayerNotInWorldException();
            }

            // Send data back to all users in this world
            //    INFO - If we have multiple messages that do not change the map
            //    -> We can modify MessageInterface::process to return a boolean
            //    -> Only if it is true, we need to refresh the gameStorage
            $this->triggerWorldUpdateEvent($world);
        } catch (MessageNotFoundException) {
            dump(
                sprintf('-> Message not found with content "%s"', $jsonMessageContent)
            );
        } catch (IncorrectMessageFormatException) {
            dump(
                sprintf('-> Incorrect message with content "%s"', $jsonMessageContent)
            );
        } catch (PlayerNotFoundException) {
            dump('-> Player not found');
        } catch (PlayerNotInWorldException) {
            dump('-> Player not in world');
        }
    }

    /**
     * @throws PlayerNotFoundException
     */
    public function getPlayerByConnection(ConnectionInterface $connection): PlayerInterface
    {
        try {
            return $this->players->offsetGet($connection);
        } catch (\UnexpectedValueException) {
            throw new PlayerNotFoundException();
        }
    }

    private function triggerWorldUpdateEvent(WorldInterface $world): void
    {
        $this->eventDispatcher->dispatch(
            new GameWorldUpdateEvent($world)
        );
    }
}