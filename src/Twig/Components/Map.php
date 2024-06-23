<?php

namespace App\Twig\Components;

use App\Entity\MineSweeper;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent()]
final class Map
{
    use DefaultActionTrait;

    #[LiveProp(useSerializerForHydration:true)]
    public MineSweeper $mineSweeper;

    #[LiveProp()]
    public bool $gameOver = false;

    public function mount(int $width, int $height, int $difficulty)
    {
        $this->mineSweeper = new MineSweeper($width, $height, $difficulty);
        $this->mineSweeper->addBomb(2,2);
    }
 
    #[LiveAction]
    public function mine(#[LiveArg()] int $x, #[LiveArg()] int $y)
    {
        try {
            if (!$this->gameOver) {
                $this->mineSweeper->mine($x, $y);
            }
        } catch (\Exception $e) {
            $this->gameOver = true;   
        }
    }

    #[LiveAction]
    public function reset(#[LiveArg()] int $width, #[LiveArg()] int $height)
    {
        $this->gameOver = false;
        
        $this->mount($width, $height, 7);
    }
}
