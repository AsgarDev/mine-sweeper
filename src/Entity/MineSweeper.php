<?php

namespace App\Entity;

use Exception;

class MineSweeper
{
    private array $map = [];

    public function __construct(
        private int $width, 
        private int $height,
        private int $difficulty = 0,
        private array $bombs = []
    ){
        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                $this->map[$y][$x] = -1;
            }
        }

        if ($difficulty) {
            $this->generateBombs($difficulty);
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

    public function setMap(array $map): self
    {
        $this->map = $map;
        
        return $this;
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

        if ($this->map[$y][$x] === 0) { 
            $this->checkAdjacentWithoutBomb($y, $y);
        }
    }   

    private function getAdjacentBombs(int $x, int $y): int
    {
        $bombs = 0;

        for ($i = -1; $i <= 1; $i++) {
            for ($j = -1; $j <= 1; $j++) {
                if (isset($this->getBombs()[$y + $j][$x + $i])) {
                    $bombs++;
                }
            }
        }

        return $bombs;
    }

    public function checkAdjacentWithoutBomb(int $x, int $y): void
    {
        for ($i = -1; $i <= 1; $i++) {
            for ($j = -1; $j <= 1; $j++) {
                if (
                    isset($this->map[$y + $j][$x + $i]) 
                    && $this->map[$y + $j][$x + $i] === -1
                    && !isset($this->getBombs()[$y + $j][$x + $i])
                    ) 
                {
                    $this->mine($x + $i, $y + $j);
                }
            }
        }
    }

    public function generateBombs(int $bombs): void
    {
        $i = 0;
        while ($i < $bombs) {
            $x = rand(0, $this->getWidth() - 1);
            $y = rand(0, $this->getHeight() - 1);

            if (!isset($this->getBombs()[$y][$x])) {
                $this->bombs[$y][$x] = 1;
                $i++;
            }
        }
    }
}
