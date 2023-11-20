<?php

namespace App\Game;

use App\Game\Contracts\PlayerInterface;
use App\Game\Contracts\WorldInterface;
use App\Game\Exception\PlayerAlreadyInWorldException;
use App\Game\Exception\WorldFullException;

class World implements WorldInterface, \JsonSerializable
{
    /**
     * @var \SplObjectStorage<PlayerInterface> $players
     */
    private \SplObjectStorage $players;

    public function __construct(
        private readonly int $index,
        private readonly int $maxPlayers,
        private readonly Map $map
    )
    {
        $this->players = new \SplObjectStorage();
    }

    public function addPlayer(PlayerInterface $player): void
    {
        if (!$this->canAcceptPlayer()) {
            throw new WorldFullException();
        }

        if ($this->hasPlayer($player)) {
            throw new PlayerAlreadyInWorldException();
        }

        $this->players->attach($player);
    }

    public function removePlayer(PlayerInterface $player): void
    {
        $this->players->detach($player);
    }

    public function hasPlayer(PlayerInterface $player): bool
    {
        return $this->players->contains($player);
    }

    public function canAcceptPlayer(): bool
    {
        return ($this->players->count() < $this->maxPlayers);
    }

    public function getMap(): Map
    {
        return $this->map;
    }

    public function getIndex(): int
    {
        return $this->index;
    }

    public function movePlayerTo(PlayerInterface $player, Vector2 $position): void
    {
        // INFO - Need to check for collisions here and find the closest correct position

        $player->moveTo($position);
    }

    public function getPlayers(): array
    {
        return iterator_to_array($this->players);
    }

    public function jsonSerialize(): array
    {
        return [
            'grid' => $this->map->getGrid(),
            'players' => iterator_to_array($this->players)
        ];
    }
}