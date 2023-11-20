<?php

namespace App\Game\Event;

use App\Game\Contracts\WorldInterface;
use Symfony\Contracts\EventDispatcher\Event;

class GameWorldUpdateEvent extends Event
{
    public function __construct(
        private readonly WorldInterface $world
    )
    {
    }

    public function getWorld(): WorldInterface
    {
        return $this->world;
    }
}