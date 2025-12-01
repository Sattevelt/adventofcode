<?php

declare(strict_types=1);

require_once('common.php');
$input = getPuzzleInput(17);
//$input = "target area: x=20..30, y=-10..-5";
//$input = "target area: x=20..30, y=5..10";

$regex = '/^target area: x=(-?[0-9]+)\.\.(-?[0-9]+), y=(-?[0-9]+)\.\.(-?[0-9]+)$/';
$matches = [];
preg_match($regex, $input, $matches);
$target = [
    'minX' => (int)min($matches[1], $matches[2]),
    'maxX' => (int)max($matches[1], $matches[2]),
    'minY' => -(int)max($matches[3], $matches[4]),
    'maxY' => -(int)min($matches[3], $matches[4]),
];
$field = [
    'minX' => 30,
    'maxX' => $target['maxX'],
    'minY' => min($target['minY'], 0) - 20,
    'maxY' => max($target['maxY'], 0) + 20,
];

$minX = getMinimumXVelocityForRange($target['minX'], $target['maxX']);
$maxY = -max(abs($target['minY']), abs($target['maxY']));
while(true) {
    if (hits($minX, $maxY, $target)) {
        break;
    }
    $maxY++;
}

$points = getPointsForVelocities($minX, $maxY, $field['maxX'], $field['maxY']);
output($field, $target, $points);
var_dump(getMaxYPos(-$maxY)); // answer

function getPointsForVelocities($vX, $vY, $maxX, $maxY): array
{
    $points = [];
    $curX = 0;
    $curY = 0;

    while ($vY < 0 || ($curX <= $maxX && $curY <= $maxY)) {
        $curX += $vX;
        $curY += $vY;
        $points[] = [$curX, $curY];


        $vX > 0 ? $vX-- : ($vX < 0 ? $vX++ : null);
        $vY++;
    }

    return $points;
}

function hits(int $vX, int $vY, array $target)
{
    $curX = 0;
    $curY = 0;

    while ($vY < 0 || ($curX <= $target['maxX'] && $curY <= $target['maxY'])) {
        $curX += $vX;
        $curY += $vY;

        if (isInField($curX, $curY, $target)) {
            return true;
        }

        $vX > 0 ? $vX-- : ($vX < 0 ? $vX++ : null);
        $vY++;
    }

    return false;
}

function output(array $field, array $target, array $points)
{
    $output = "\n";
    $maxX = max($field['minX'], $field['maxX']);
    $minX = min($field['minX'], $field['maxX']);
    $maxY = max($field['minY'], $field['maxY']);
    $minY = min($field['minY'], $field['maxY']);

    for ($y = $minY; $y <= $maxY; $y++) {
        for ($x = $minX; $x <= $maxX; $x++) {
            if ($x === 0 && $y === 0) {
                $output .= 'S';
            } elseif (in_array([$x, $y], $points)) {
                $output .= '#';
            } elseif (isInField($x, $y, $target)) {
                $output .= 'T';
            } else {
                $output .= '.';
            }
        }
        $output .= PHP_EOL;
    }

    echo $output;
}

function isInField(int $x, int $y, array $field)
{
    return $y >= $field['minY'] && $y <= $field['maxY'] && $x >= $field['minX'] && $x <= $field['maxX'];
}

function getMinimumXVelocityForRange(int $xMin, int $xMax)
{
    $found = false;
    $startV = 0;
    $curX = 0;
    while(! $found) {
        $curV = $startV;
        while ($curV !== 0) {
            $curX += $curV;
            $curV > 0 ? $curV-- : ($curV < 0 ? $curV++ : null);
        }
        if ($xMin <= $curX && $curX <= $xMax) {
            break;
        }
        $curX = 0;
        $startV++;
    }
    return $startV;
}

function getMaxYPos(int $vY): int
{
    $curY = 0;
    $prevY = 0;
    while($prevY <= $curY) {
        $prevY = $curY;
        $curY += $vY;

        $vY--;
    }
    return $prevY;
}


