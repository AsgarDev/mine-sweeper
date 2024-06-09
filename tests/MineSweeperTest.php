<?php

use App\Entity\MineSweeper;
use PHPUnit\Framework\TestCase;

class MineSweeperTest extends TestCase
{
    private MineSweeper $mineSweeper;

    public function setUp(): void
    {
        $this->mineSweeper = new MineSweeper(5, 10);
    }

    public function testMapSize()
    {
        $this->assertSame(5, $this->mineSweeper->getWidth());
        $this->assertSame(10, $this->mineSweeper->getHeight());
        $this->assertCount(10, $this->mineSweeper->getMap());
        $this->assertCount(5, $this->mineSweeper->getMap()[0]);
    }

    public function testAddBomb()
    {
        $this->mineSweeper->addBomb(2,2);
        $this->mineSweeper->addBomb(2,3);
        $this->assertSame(1, $this->mineSweeper->getBombs()[2][2]);
        $this->assertSame(1, $this->mineSweeper->getBombs()[3][2]);
    }

    public function testBoom()
    {
        $this->expectExceptionMessage('BOOM');
        $this->mineSweeper->addBomb(2,2);
        $this->mineSweeper->mine(2,2); 
    }

    public function testOutOfMapX()
    {
        $this->expectExceptionMessage('Out of Map');
        $this->mineSweeper->mine(-1,2); 
    }

    public function testOutOfMapY()
    {
        $this->expectExceptionMessage('Out of Map');
        $this->mineSweeper->mine(2,20); 
    }

    public function testMine()
    {
        $this->assertSame(-1, $this->mineSweeper->getMap()[2][3]);
        $this->mineSweeper->mine(3,2);
        $this->assertNotSame(-1, $this->mineSweeper->getMap()[2][3]);
    }

    public function testAdjacentMine()
    {
        $this->mineSweeper->addBomb(2,2);
        $this->mineSweeper->mine(3,2);
        $this->assertSame(1, $this->mineSweeper->getMap()[2][3]);

        $this->assertSame(-1, $this->mineSweeper->getMap()[1][1]);
        $this->mineSweeper->mine(1,1);
        $this->assertSame(1, $this->mineSweeper->getMap()[1][1]);

        $this->assertSame(-1, $this->mineSweeper->getMap()[5][1]);
        $this->mineSweeper->mine(1,5);
        $this->assertSame(0, $this->mineSweeper->getMap()[5][1]);
    }
}
