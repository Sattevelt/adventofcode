<?php

$input = AOC\Lib\Input::inputAsArray($input);

foreach ($input as $key => $value) {
    if ($value == '') {
        continue;
    }
    $input[$key] = explode(' ', $value);
}

$numSafe = 0;
foreach ($input as $values) {
//    var_dump($values);
    $dir = $values[0] <=> $values[1];

    foreach ($values as $key => $value) {
        if (!array_key_exists($key + 1, $values)) {
            break;
        }


        if ($dir !== ($value <=> $values[$key + 1])) {
            continue 2;
        }
        $diff = abs($value - $values[$key + 1]);
        if ($diff < 1 || $diff > 3) {
            continue 2;
        }
    }
    $numSafe++;
}

return $numSafe;