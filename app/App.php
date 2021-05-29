<?php

namespace Robots;

class App
{
	public function runRobots(array $argv)
	{
		// You would typically want input validation - instructions were to assume correct input
		$file = fopen($argv[1], "r");
		
		// Used to determine where each block is, and what blocks each position contains
		$blocks = $positions = []; // These could be class properties.
		
		// Read first line and setup arrays
		$numBlocks = fgets($file);
		for ($i = 0; $i<$numBlocks; $i++) {
			$blocks[$i]    = $i;
			$positions[$i] = [$i];
		}
		
		// Loop through each line until end of file or "quit"
		while (($line = fgets($file)) !== false) {
			if ($line === "quit") {
				break;
			}
			
			// Our input is space delimited, get each component
			$cmdComponents = explode(" ", $line);

			// Determine which function to use based on input line
			$funcName = $cmdComponents[0] . ucfirst($cmdComponents[2]);
			self::performValidCommand($funcName, (int)$cmdComponents[1], (int)$cmdComponents[3], $blocks, $positions);
		}
		fclose($file);
		
		// Create output, Implode makes sure we don't have extra whitespace or linebreaks
		$output = [];
		foreach ($positions as $positionKey => $positionValue) {
			$output[] = trim($positionKey . ": " . implode(" ", $positionValue));
		}
		echo implode(PHP_EOL, $output);
	}
	
	// Perform command if passes validation - If I had separate classes this would be the only public entry point to the
	// commands to ensure validation happens.
	public static function performValidCommand($command, $a, $b, &$blocks, &$positions) {
		if ($a !== $b && $blocks[$a] !== $blocks[$b]) {
			self::$command($a, $b, $blocks, $positions);
		}
	}
	
	// Put block a onto block b after returning any blocks that are stacked on top of blocks a and b to initial positions
	public static function moveOnto($a, $b, &$blocks, &$positions) {
		$targetPosition = $blocks[$b];
		
		self::moveBlocksAbove($a, $blocks, $positions);
		self::moveBlocksAbove($b, $blocks, $positions);
		
		self::moveBlock($a, $targetPosition, $blocks, $positions);
	}

	// Put block a onto the top of the stack containing block b, after returning any blocks that are stacked on top of
	// block a to their initial positions
	public static function moveOver($a, $b, &$blocks, &$positions) {
		$targetPosition = $blocks[$b];
		
		self::moveBlocksAbove($a, $blocks, $positions);
		
		self::moveBlock($a, $targetPosition, $blocks, $positions);
	}
	
	// Move the pile of blocks consisting of block a, and any blocks that are stacked above block a, onto block b. all
	// blocks on top of block b are moved to their initial positions prior to the pile taking place. The blocks stacked
	// above block a retain their order when moved
	public static function pileOnto($a, $b, &$blocks, &$positions) {
		$targetPosition = $blocks[$b];
		
		self::moveBlocksAbove($b, $blocks, $positions);
		self::moveBlocksAbove($a, $blocks, $positions, true, $targetPosition);
	}

	// Put the pile of blocks consisting of block a, and any blocks that are stacked above block a, onto the top of the
	// stack containing block b. The blocks stacked above block a retain their original order when moved.
	public static function pileOver($a, $b, &$blocks, &$positions) {
		$targetPosition = $blocks[$b];
		
		self::moveBlocksAbove($a, $blocks, $positions, true, $targetPosition);
	}
	
	// Move pile of blocks above specified block to target position - initial position if no target specified
	private static function moveBlocksAbove($block, &$blocks, &$positions, $inclusive = false, $targetPosition = null) {		
		$move = false;
		foreach ($positions[$blocks[$block]] as $blockToMove) {
			if ($blockToMove === $block) {
				$move = true;
				
				// If inclusive = false - skip specified block 
				if (is_null($targetPosition)) {
					continue;
				}
			}
			
			// Move block to target position (or initial position if no target specified)
			if ($move) {
				self::moveBlock($blockToMove, $targetPosition ?? $blockToMove, $blocks, $positions);
			}
		}
	}
	
	// Move block from one pile to the top of another
	private static function moveBlock($block, $targetPosition, &$blocks, &$positions) {
		$currentPosition = $blocks[$block];
		
		// Update position in block list
		$blocks[$block] = $targetPosition;
		
		// Add block on top of new position
		$positions[$targetPosition][] = $block;

		// Remove block from previous position - array_search to get index within pile
		unset($positions[$currentPosition][array_search($block, $positions[$currentPosition])]);
	}
}
