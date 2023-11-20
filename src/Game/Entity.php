<?php

namespace App\Game;

abstract class Entity
{
    public function __construct(
        protected readonly int $id,
        protected Vector2 $position,
        protected string $spriteUrl
    )
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPosition(): Vector2
    {
        return $this->position;
    }

    public function getSpriteUrl(): string
    {
        return $this->spriteUrl;
    }
}