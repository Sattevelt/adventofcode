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
    'minX' => 00,
    'maxX' => $target['maxX'],
    'minY' => min($target['minY'], 0) - 20,
    'maxY' => max($target['maxY'], 0) + 20,
];

$maxX = $target['maxX'];
$minX = getMinimumXVelocityForRange($target['minX'], $target['maxX']);
$maxY = -max(abs($target['minY']), abs($target['maxY']));
while(true) {
    if (hits($minX, $maxY, $target)) {
        break;
    }
    $maxY++;
}
$minY = -$maxY;

$count = 0;
for ($x = $minX - 1; $x <= $maxX + 1; $x++) {
    for ($y = $minY + 1; $y >= $maxY - 1; $y--) {
        if (hits($x, $y, $target)) {
            $count++;
        }
    }
}
var_dump($count);die;

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
