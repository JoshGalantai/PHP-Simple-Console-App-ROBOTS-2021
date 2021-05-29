<?php

use PHPUnit\Framework\TestCase;
use Robots\App;

class RobotTest extends TestCase
{
	public const POSITIONS = [
		0 => [0],
		1 => [1, 2, 3],
		2 => [4, 5, 6],
		3 => [],
		4 => [],
		5 => [],
		6 => [],
		7 => [7],
		8 => [8],
		9 => [9],
	];
	
	const BLOCKS = [
		0 => 0,
		1 => 1,
		2 => 1,
		3 => 1,
		4 => 2,
		5 => 2,
		6 => 2,
		7 => 7,
		8 => 8,
		9 => 9,
	];
	
	public function testMoveOnto()
	{
		$positions = self::POSITIONS;
		$blocks    = self::BLOCKS;
		
		App::moveOnto(2, 5, $blocks, $positions);
		
		$this->assertEquals($blocks, [
			0 => 0,
			1 => 1,
			2 => 2,
			3 => 3,
			4 => 2,
			5 => 2,
			6 => 6,
			7 => 7,
			8 => 8,
			9 => 9,
		]);
		
		$this->assertEquals($positions, [
			0 => [0],
			1 => [1],
			2 => [0 => 4, 1 => 5, 3 => 2],
			3 => [3],
			4 => [],
			5 => [],
			6 => [6],
			7 => [7],
			8 => [8],
			9 => [9],
		]);
	}
	
	public function testMoveOver()
	{
		$positions = self::POSITIONS;
		$blocks    = self::BLOCKS;
		
		App::moveOver(2, 5, $blocks, $positions);
		
		$this->assertEquals($blocks, [
			0 => 0,
			1 => 1,
			2 => 2,
			3 => 3,
			4 => 2,
			5 => 2,
			6 => 2,
			7 => 7,
			8 => 8,
			9 => 9,
		]);
		
		$this->assertEquals($positions, [
			0 => [0],
			1 => [1],
			2 => [4, 5, 6, 2],
			3 => [3],
			4 => [],
			5 => [],
			6 => [],
			7 => [7],
			8 => [8],
			9 => [9],
		]);
	}
	
	public function testPileOnto()
	{
		$positions = self::POSITIONS;
		$blocks    = self::BLOCKS;
		
		App::pileOnto(2, 5, $blocks, $positions);
		
		$this->assertEquals($blocks, [
			0 => 0,
			1 => 1,
			2 => 2,
			3 => 2,
			4 => 2,
			5 => 2,
			6 => 6,
			7 => 7,
			8 => 8,
			9 => 9,
		]);
		
		$this->assertEquals($positions, [
			0 => [0],
			1 => [1],
			2 => [0 => 4, 1 => 5, 3 => 2, 4 => 3],
			3 => [],
			4 => [],
			5 => [],
			6 => [6],
			7 => [7],
			8 => [8],
			9 => [9],
		]);
	}
	
	public function testPileOver()
	{
		$positions = self::POSITIONS;
		$blocks    = self::BLOCKS;
		
		App::pileOver(2, 5, $blocks, $positions);
		
		print_r($positions);
		$this->assertEquals($blocks, [
			0 => 0,
			1 => 1,
			2 => 2,
			3 => 2,
			4 => 2,
			5 => 2,
			6 => 2,
			7 => 7,
			8 => 8,
			9 => 9,
		]);
		
		$this->assertEquals($positions, [
			0 => [0],
			1 => [1],
			2 => [4, 5, 6, 2, 3],
			3 => [],
			4 => [],
			5 => [],
			6 => [],
			7 => [7],
			8 => [8],
			9 => [9],
		]);
	}
}
