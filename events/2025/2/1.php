<?php

$input = explode(',', trim($input));

$patternLengths = [
    0 => [],
    1 => [],
    2 => [1],
    3 => [],
    4 => [2],
    5 => [],
    6 => [3],
    7 => [],
    8 => [4],
    9 => [],
    10 => [5]
];


$answer = 0;
foreach($input as $value) {
    $values = explode('-', $value);
    $minLength = strlen($values[0]);
    $maxLength = strlen($values[1]);
    $min = $values[0];
    $max = $values[1];
    echo "Checking $value:\n";
    if ($minLength < $maxLength) {
        // Do two runs;
        $tempMax = str_repeat('9', $minLength);
        $tempMin = '1' . str_repeat('0', $maxLength - 1);
        $answer += getPatternsInRange($min, $tempMax, $patternLengths[$minLength]);
        $answer += getPatternsInRange($tempMin, $max, $patternLengths[$maxLength]);
    } else {
        $answer += getPatternsInRange($min, $max, $patternLengths[$minLength]);
    }
}
return $answer;


function getPatternsInRange(string $min, string $max, array $patternLengths): int
{
    $answer = 0;
    foreach ($patternLengths as $patLength) {
        $checkFrom = substr($min, 0, $patLength);
        $checkTo = substr($max, 0, $patLength);
        echo "- Checking pattern $checkFrom to $checkTo\n";
        for ($i = (int)$checkFrom; $i <= (int)$checkTo; $i++) {
            $testNum = str_repeat($i, strlen($min) / $patLength);
            echo "  - Testing $testNum";
            if ($testNum >= $min && $testNum <= $max) {
                $answer += (int)$testNum;
                echo " - PASS\n";
            } else {
                echo " - FAIL\n";
            }
        }
    }
    return $answer;
}
