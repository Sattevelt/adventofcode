<?php

$input = AOC\Lib\Input::inputAsArray($input);

$regex = '/(L|R)(\d+)/';
$matches = [];
$pos = 50;
$numZeros = 0;
foreach ($input as $line) {
    $result = preg_match($regex, $line, $matches);
    $num = (int)$matches[2] % 100;
    if ($matches[1] === 'L') {
        $num *= -1;
    }

    $pos += $num;
    if ($pos > 99) {
        $pos -= 100;
    } elseif ($pos < 0) {
        $pos = 100 + $pos;
    }

    if ($pos === 0) {
        $numZeros++;
    }
}


return $numZeros;