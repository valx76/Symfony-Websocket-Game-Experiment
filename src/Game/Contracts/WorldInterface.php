<?php

namespace App\Game\Contracts;

use App\Game\Exception\PlayerAlreadyInWorldException;
use App\Game\Exception\WorldFullException;
use App\Game\Map;
use App\Game\Vector2;

interface WorldInterface
{
    /**
     * @throws WorldFullException
     * @throws PlayerAlreadyInWorldException
     */
    public function addPlayer(PlayerInterface $player): void;

    public function removePlayer(PlayerInterface $player): void;

    public function hasPlayer(PlayerInterface $player): bool;

    public function canAcceptPlayer(): bool;

    public function getMap(): Map;

    public function getIndex(): int;

    /**
     * @return PlayerInterface[]
     */
    public function getPlayers(): array;

    public function movePlayerTo(PlayerInterface $player, Vector2 $position): void;
}