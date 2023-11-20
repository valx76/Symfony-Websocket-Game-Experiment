<?php

namespace App\Game\Contracts;

use App\Game\GameServer;
use Ratchet\ConnectionInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.message')]
interface MessageInterface
{
    public function process(GameServer $gameServer, ConnectionInterface $connection, mixed $content): void;

    public function supports(int $id): bool;
}