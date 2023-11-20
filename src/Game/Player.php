<?php

namespace App\Game;

use App\Game\Contracts\PlayerInterface;
use App\Game\Contracts\WorldInterface;
use Ratchet\ConnectionInterface;

class Player extends Entity implements PlayerInterface, \JsonSerializable
{
    private ?WorldInterface $world;

    public function __construct(
        int $id,
        Vector2 $position,
        protected readonly string $name,
        protected readonly ConnectionInterface $connection
    )
    {
        parent::__construct($id, $position, 'TODO');

        $this->world = null;
    }

    public function getConnection(): ConnectionInterface
    {
        return $this->connection;
    }

    public function setWorld(?WorldInterface $world): void
    {
        $this->world = $world;
    }

    public function getWorld(): ?WorldInterface
    {
        return $this->world;
    }

    /**
     * The position must be verified before calling this method!
     */
    public function moveTo(Vector2 $position): void
    {
        $this->position = $position;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'position' => $this->position,
            'name' => $this->name
        ];
    }
}