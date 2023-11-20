<?php

namespace App\Game;

class Vector2
{
    public function __construct(
        public readonly int $x,
        public readonly int $y
    )
    {
    }

    public function add(Vector2 $vec): self
    {
        return new Vector2(
            $this->x + $vec->x,
            $this->y + $vec->y,
        );
    }

    public function __toString(): string
    {
        return sprintf('(%d,%d)', $this->x, $this->y);
    }
}