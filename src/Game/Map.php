<?php

namespace App\Game;

use App\Game\Exception\MapFileFormatException;
use App\Game\Exception\MapFileUnreadableException;

class Map
{
    /**
     * @var int[][] $grid
     */
    private array $grid;

    /**
     * @throws MapFileFormatException
     * @throws MapFileUnreadableException
     */
    public function __construct(
        string $mapFilePath
    )
    {
        $this->grid = [];

        $fileHandle = fopen($mapFilePath, 'r');

        if ($fileHandle === false) {
            throw new MapFileUnreadableException();
        }

        $currentLine = 0;

        while (($line = fgets($fileHandle)) !== false) {
            $this->grid[$currentLine] = [];

            $tiles = explode(' ', $line);

            foreach ($tiles as $tile) {
                if (!is_numeric($tile)) {
                    throw new MapFileFormatException();
                }

                $this->grid[$currentLine][] = intval($tile);
            }

            $currentLine++;
        }

        fclose($fileHandle);
    }

    /**
     * @return int[][]
     */
    public function getGrid(): array
    {
        return $this->grid;
    }
}