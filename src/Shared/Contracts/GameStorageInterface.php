<?php

namespace App\Shared\Contracts;

use App\Game\Exception\GameStorageConnectException;
use App\Game\Exception\GameStorageDataException;
use App\Game\Exception\GameStorageNotConnectedException;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.game_storage')]
interface GameStorageInterface
{
    /**
     * @throws GameStorageConnectException
     */
    public function connect(string $host, int $port): void;

    /**
     * @throws GameStorageNotConnectedException
     */
    public function ping(): bool;

    /**
     * @throws GameStorageDataException
     * @throws GameStorageNotConnectedException
     */
    public function set(string $key, string $value): void;

    /**
     * @throws GameStorageDataException
     * @throws GameStorageNotConnectedException
     */
    public function get(string $key): string;
}