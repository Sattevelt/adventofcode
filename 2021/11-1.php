<?php

declare(strict_types=1);

require_once('common.php');
$input = getPuzzleInput(11);
$input = explode("\r\n", $input);
//$input = explode("\n", "5483143223
//2745854711
//5264556173
//6141336146
//6357385478
//4167524645
//2176841721
//6882881134
//4846848554
//5283751526");

//$input = "1111119991191911999111111";

$size = strlen($input[0]);
$dirOffsets = [
    't' => [0, -1],
    'tr' => [1, -1],
    'r' => [1, 0],
    'br' => [1, 1],
    'b' => [0, 1],
    'bl' => [-1, 1],
    'l' => [-1, 0],
    'tl' => [-1, -1]
];

$iterations = 100;
$state = $input;
print_r($state);
$flashes = 0;
for ($i = 1; $i <= $iterations; $i++) {
    //Fill the stack
    $stack = [];

    foreach ($state as $y => $row) {
        foreach (str_split($row) as $x => $char) {
            $char++;
            if ($char > 9) {
                $stack[] = [$x, $y];
                $char = 0;
                $flashes++;
            }
            $state[$y] = substr_replace($state[$y], (string)$char, $x, 1);
        }
    }

    // Apply the stack
    while (count($stack) > 0) {
        $curCoord = array_shift($stack);
        foreach ($dirOffsets as $dir => $offset) {
            $newCoord = [$curCoord[0] + $offset[0], $curCoord[1] + $offset[1]];
            if ($newCoord[0] < 0 || $newCoord[0] >= $size || $newCoord[1] < 0 || $newCoord[1] >= $size) {
                continue; // Out of bounds
            }
            $curVal = (int)substr($state[$newCoord[1]], $newCoord[0], 1);
            if ($curVal === 0) {
                continue; // already flashed
            }
            $curVal++;
            if ($curVal > 9) {
                $curVal = 0;
                if (! in_array($newCoord, $stack, true)) {
                    $stack[] = $newCoord;
                    $flashes++;
                }
            }
            $state[$newCoord[1]] = substr_replace($state[$newCoord[1]], (string)$curVal, $newCoord[0], 1);
        }
    }

//    print_r($state);
}
var_dump($flashes);





