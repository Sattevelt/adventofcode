<?php

$input = 376;
$test = 3;
//$input = $test;

$state = [0 => 0];
$pos = 0;

for ($i = 1; $i <= 2017; $i++) {
    $pos = ($pos + $input) % count($state);
    array_splice($state, $pos, 1, [$state[$pos], $i]);
    $pos++;
}

echo $state[$pos + 1];
echo "\n\n";
