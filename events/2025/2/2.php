<?php

$input = explode(',', trim($input));

$patternLengths = [
    0 => [],
    1 => [],
    2 => [1],
    3 => [1],
    4 => [1,2],
    5 => [1],
    6 => [1,2,3],
    7 => [1],
    8 => [1,2,4],
    9 => [1,3],
    10 => [1,2,5],
    11 => [1],
    12 => [1,2,3,4,6],
    13 => [1],
    14 => [1,2,7],
    15 => [1,3,5],
];

$answer = 0;
foreach($input as $value) {
    $values = explode('-', $value);
    $minLength = strlen($values[0]);
    $maxLength = strlen($values[1]);
    $min = $values[0];
    $max = $values[1];

    for ($i = $minLength; $i <= $maxLength; $i++) {
        $minInput = max((int)$min, (int)('1' . str_repeat('0', $i - 1)));
        $maxInput = min((int)$max, str_repeat('9', $i));
        $answer += getPatternsInRange((string)$minInput, (string)$maxInput, $patternLengths[$i]);
    }
}

return $answer;

function getPatternsInRange(string $min, string $max, array $patternLengths): int
{
    $answer = 0;
    $matched = [];
    foreach ($patternLengths as $patLength) {
        $checkFrom = substr($min, 0, $patLength);
        $checkTo = substr($max, 0, $patLength);
        for ($i = (int)$checkFrom; $i <= (int)$checkTo; $i++) {
            $testNum = (int)(str_repeat($i, strlen($min) / $patLength));
            if (!in_array($testNum, $matched, true) && $testNum >= $min && $testNum <= $max) {
                $answer += (int)$testNum;
                $matched[] = $testNum;
            }
        }
    }
    return $answer;
}

