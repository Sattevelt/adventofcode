<?php

$from = '136818';
$to = '685979';
$len = strlen($from);

$count = 0;
for($i = $from; $i <= $to; $i++) {
    $min = (int) substr($i, 0, 1);
    $hasDouble = false;
//echo "checking $i\n";
    for ($j = 0; $j < $len; $j++) {
        $value = (int)substr($i, $j, 1);
//echo "- $value (min: $min):\n";
        if ($value < $min) {
            continue 2;
        }
        if ($value === $min) {
            $hasDouble = true;
        }
        $min = $value;
    }

    if ($hasDouble) {
        echo "counting $i:\n";
        $count++;
    }
}
echo "$count\n";