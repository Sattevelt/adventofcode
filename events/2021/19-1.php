<?php

declare(strict_types=1);

require_once('common.php');
$input = getPuzzleInput(19);
$input = explode("\r\n", $input);
$input = explode("\n", "--- scanner 0 ---
-1,-1,1
-2,-2,2
-3,-3,3
-2,-3,1
5,6,-4
8,0,7

--- scanner 0 ---
1,-1,1
2,-2,2
3,-3,3
2,-1,3
-5,4,-6
-8,-7,0

--- scanner 0 ---
-1,-1,-1
-2,-2,-2
-3,-3,-3
-1,-3,-2
4,6,5
-7,0,8

--- scanner 0 ---
1,1,-1
2,2,-2
3,3,-3
1,3,-2
-4,-6,5
7,0,8

--- scanner 0 ---
1,1,1
2,2,2
3,3,3
3,1,2
-6,-4,-5
0,7,-8");

$scannerRegex = '/^--- scanner ([0-9]+) ---$/';
$reportRegex = '/^(-?[0-9]+)\,(-?[0-9]+)\,(-?[0-9]+)$/';
$scannerData = [];
$curIndex = 0;
foreach ($input as $line) {
    if (strlen($line) === 0) {
        continue;
    }
    $matches = [];
    if (preg_match($scannerRegex, $line, $matches) === 1) {
        $curIndex = (int)$matches[1];
        continue;
    }
    $matches = [];
    if (preg_match($reportRegex, $line, $matches) === 1) {
        $scannerData[$curIndex]['coords'][] = [(int)$matches[1], (int)$matches[2], (int)$matches[3]];
    }
}

foreach ($scannerData as $scannerIndex => $scanner) {
    foreach ($scanner['coords'] as $index1 => $coord1) {
        foreach ($scanner['coords'] as $index2 => $coord2) {
            if ($index1 === $index2 || isset($scannerData[$scannerIndex]['dist'][$index1][$index2])) {
                continue;
            }
            $dist = sqrt(
                pow($coord1[0] - $coord2[0], 2)
                + pow($coord1[1] - $coord2[1], 2)
                + pow($coord1[2] - $coord2[2], 2)
            );
            $scannerData[$scannerIndex]['dist'][$index1][$index2] = $dist;
            $scannerData[$scannerIndex]['dist'][$index2][$index1] = $dist;
        }
    }
}
print_r($scannerData);
