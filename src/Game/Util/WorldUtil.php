<?php

namespace App\Game\Util;

use App\Game\Contracts\WorldInterface;
use App\Game\Exception\NoWorldAvailableException;

class WorldUtil
{
    /**
     * @param WorldInterface[] $worlds
     * @throws NoWorldAvailableException
     */
    public static function findFirstWorldAcceptingPlayers(array $worlds): WorldInterface
    {
        foreach ($worlds as $world) {
            if ($world->canAcceptPlayer()) {
                return $world;
            }
        }

        throw new NoWorldAvailableException();
    }
}