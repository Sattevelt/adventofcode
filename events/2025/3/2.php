<?php

$input = AOC\Lib\Input::inputAsArray($input);


$totalJoltage = 0;
foreach ($input as $bankData) {
    $totalJoltage += findMaxJoltage($bankData);
}
return $totalJoltage;


function findMaxJoltage(string $bankData): int
{
//    echo "Analysing $bankData:\n";
    $minPos = 0;
    $output = '';
    $bankLength = strlen($bankData);
    for ($i = 11; $i >= 0; $i--) {
        $winner = getHighestFromBank($bankData, $minPos, $bankLength - $i);
        $minPos = $winner[0] + 1;
        $output .= $winner[1];
//        echo "- Highest: $winner[1] - (" . substr($bankData, $minPos, $bankLength - $i - $minPos) . ")\n";
    }
    echo "$output\n";
    return $output;
}

function getHighestFromBank(string $bankData, $minPos, $maxPos): array
{
    $validNums = str_split(substr($bankData, $minPos, $maxPos - $minPos));
    $max = 0;
    $curPos = null;
    foreach ($validNums as $pos => $num) {
        if ($num > $max) {
            $max = $num;
            $curPos = $pos + $minPos;
        }
    }
    return [$curPos, $max];
}
