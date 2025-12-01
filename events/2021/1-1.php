<?php

declare(strict_types=1);

require_once('common.php');
$input = getPuzzleInput(1);

$input = explode("\r\n", $input);
//$input = explode("\n", "199
//200
//208
//210
//200
//207
//240
//269
//260
//263");

$incs = 0;
$prev = null;
foreach ($input as $value) {
    $value = (int)$value;
    if ($prev !== null && $value > $prev) {
        $incs++;
    }
    $prev = $value;
}
var_dump($incs);