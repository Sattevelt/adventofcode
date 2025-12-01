<?php

declare(strict_types=1);

require_once('common.php');
$input = getPuzzleInput(13);
$input = explode("\r\n", $input);
//$input = explode("\n", "6,10
//0,14
//9,10
//0,3
//10,4
//4,11
//6,0
//6,12
//4,1
//0,13
//10,12
//3,4
//3,0
//8,4
//1,10
//2,14
//8,10
//9,0
//
//fold along y=7
//fold along x=5");

$coordRegex = '/^([0-9]+)\,([0-9]+)$/';
$foldRegex = '/^fold along (x|y)\=([0-9]+)$/';
$points = [];
$folds = [];
$keyFormat = '%s|%s';
foreach ($input as $line) {
    $matches = [];
    if (preg_match($coordRegex, $line, $matches)) {
        $points[sprintf($keyFormat, $matches[1], $matches[2])] = [(int)$matches[1], (int)$matches[2]];
    } elseif (preg_match($foldRegex, $line, $matches)) {
        $folds[] = [$matches[1], $matches[2]];
    }
}

foreach ($folds as $index => $fold) {
    $coordKey = $fold[0] === 'x' ? 0 : 1;
    $axisValue = $fold[1];
    $newPoints = [];

    foreach ($points as $point) {
        $newPoint = $point;
        if ($point[$coordKey] === $axisValue) {
            continue; // On fold line;
        } elseif ($point[$coordKey] > $axisValue) {
            // Below or right of fold line
            $newPoint[$coordKey] = abs($axisValue + ($axisValue - $newPoint[$coordKey]));
        }
        $newPoints[sprintf($keyFormat, $newPoint[0], $newPoint[1])] = $newPoint;
    }
    $points = $newPoints;
}

$maxX = 0;
$maxY = 0;
foreach ($points as $point) {
    $maxX = max($maxX, $point[0]);
    $maxY = max($maxY, $point[1]);
}

for ($y = 0; $y <= $maxY; $y++) {
    for ($x = 0; $x <= $maxX; $x++) {
        $coord = [$x,$y];
        echo (array_key_exists(sprintf($keyFormat, $x, $y), $points) ? '#' : '.');
    }
    echo PHP_EOL;
}
