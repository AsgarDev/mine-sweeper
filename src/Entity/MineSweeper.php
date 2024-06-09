<?php

namespace App\Entity;

use Exception;

class MineSweeper
{
    private array $map = [];
    private array $bombs = [];

    public function __construct(
        private int $width, 
        private int $height
    ){
        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                $this->map[$y][$x] = -1;
            }
        }
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function getMap(): array
    {
        return $this->map;
    }

    public function addBomb(int $x, int $y): void
    {
        $this->bombs[$y][$x] = 1;
    }

    public function getBombs(): array
    {
        return $this->bombs;
    }

    public function mine(int $x, int $y): void
    {
        if (isset($this->getBombs()[$y][$x])) {
            throw new Exception('BOOM');
        }

        if (!isset($this->getMap()[$y][$x])) {
            throw new Exception('Out of Map');
        }

        $this->map[$y][$x] = $this->getAdjacentBombs($x, $y);
    }   

    private function getAdjacentBombs(int $x, int $y): int
    {
        $bombs = 0;

        for ($i=-1; $i <= 1; $i++) {
            for ($j=-1; $j <= 1; $j++) {
                if (isset($this->getBombs()[$x + $i][$y + $j])) {
                    $bombs++;
                }
            }
        }

        return $bombs;
    }
}
