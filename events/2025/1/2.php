<?php

$input = AOC\Lib\Input::inputAsArray($input);

$regex = '/(L|R)(\d+)/';
$matches = [];
$pos = 50;
$numZeros = 0;
foreach ($input as $line) {
    $result = preg_match($regex, $line, $matches);
    $num = (int)$matches[2] % 100;
    if ($matches[2] > 100) {
        $numZeros += floor($matches[2] / 100);
        var_dump('adding');
    }
    if ($matches[1] === 'L') {
        $num *= -1;
    }
echo "$num -> ";
    $origPos = $pos;
    $pos += $num;
    if ($pos > 99) {
        $pos -= 100;
        if ($pos != 0 && $origPos != 0) {
            $numZeros++;
            echo 'over';
        }
    } elseif ($pos < 0) {
        $pos = 100 + $pos;
        if ($pos != 0 && $origPos != 0) {
            echo 'under';
            $numZeros++;
        }
    }

    if ($pos === 0) {
        $numZeros++;
        echo 'on';
    }
    echo "\n";
}


return $numZeros;