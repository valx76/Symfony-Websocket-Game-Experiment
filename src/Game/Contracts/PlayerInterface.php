<?php

namespace App\Game\Contracts;

use App\Game\Vector2;
use Ratchet\ConnectionInterface;

interface PlayerInterface
{
    public function getConnection(): ConnectionInterface;

    public function setWorld(?WorldInterface $world): void;

    public function getWorld(): ?WorldInterface;

    public function moveTo(Vector2 $position): void;
}