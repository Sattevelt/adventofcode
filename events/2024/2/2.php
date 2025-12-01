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

    if (!isSafe($values)) {

        for ($i = 0; $i < count($values); $i++) {
            $copy = $values;
            array_splice($copy, $i, 1);
            if (isSafe($copy)) {
                $numSafe++;
                continue 2;
            }
        }
    } else {
        $numSafe++;
    }
}

return $numSafe;

function isSafe($input): bool
{
    $dir = $input[0] <=> $input[1];

    foreach ($input as $key => $value) {
        if (!array_key_exists($key + 1, $input)) {
            break;
        }

        if ($dir !== ($value <=> $input[$key + 1])) {
            return false;
        }
        $diff = abs($value - $input[$key + 1]);
        if ($diff < 1 || $diff > 3) {
            return false;
        }
    }
    return true;
}