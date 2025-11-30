<?php

$input = '0: 4
1: 2
2: 3
4: 5
6: 8
8: 4
10: 6
12: 6
14: 6
16: 10
18: 6
20: 12
22: 8
24: 9
26: 8
28: 8
30: 8
32: 12
34: 12
36: 12
38: 8
40: 10
42: 14
44: 12
46: 14
48: 12
50: 12
52: 12
54: 14
56: 14
58: 14
60: 12
62: 14
64: 14
68: 12
70: 14
74: 14
76: 14
78: 14
80: 17
82: 28
84: 18
86: 14';

$test = '0: 3
1: 2
4: 4
6: 4';

//$input = $test;

$parsed = [];
$regex = "/^([\d]+)\: ([\d]+)$/";
$curLayer = 0;
foreach (explode("\n", $input) as $line) {
    $matches = [];
    preg_match($regex, $line , $matches);
    $layer = (int)$matches[1];
    $range = (int)$matches[2];

    if ($layer > $curLayer) {
        for ($i = $curLayer; $i < $layer; $i++) {
            $parsed[$i] = null;
            $curLayer++;
        }
    }

    $down = range(0, $range - 1, 1);
    $up = $range > 2 ? range($range - 2, 1, -1) : [];

    $parsed[$matches[1]] = [
        'r' => $range,
        'v' => array_merge($down, $up)
    ];
    $curLayer++;
}

$tick = 0;
$severity = 0;
foreach ($parsed as $layer => $range) {
    if ($range !== null) {
        $scannerPos = $range['v'][($tick % count($range['v']))];
        if ($scannerPos === 0) {
            echo sprintf("Detection at layer %s. Score added: %s\n", $layer, $layer * $range['r']);
            $severity += $layer * $range['r'];
        }
    }
    $tick++;
}

echo sprintf("Final severity score: %s\n\n", $severity);
