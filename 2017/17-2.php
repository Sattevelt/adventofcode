<?php

$input = 376;
$test = 3;
//$input = $test;

$state = [0 => 0];
$pos = 0;
$val = INF;
for ($i = 1; $i <= 50000000; $i++) {
    $pos = ($pos + $input) % $i;
    if ($pos === 0) {
        $val = $i;
        echo $val . "\n";
    }
    $pos++;
}

