<?php

$input = AOC\Lib\Input::inputAsArray($input);


$totalJoltage = 0;
foreach ($input as $bankData) {
    $totalJoltage += findMaxJoltage($bankData);
}
return $totalJoltage;


function findMaxJoltage(string $bankData): int
{
    $analysis = getBankAnalysis($bankData);
    echo "Analysing $bankData\n";
    foreach ($analysis as $firstNum => $positions) {
        // Get leftmost occurence of the num.
        $firstPos = $positions[0];
        echo "- Considering $firstNum";
        $restOfBank = substr($bankData, $firstPos + 1);
        if ($restOfBank == '') {
            continue;
        }
        $secondAnalysis = getBankAnalysis($restOfBank);
        $secondNum = array_key_first($secondAnalysis);
        echo "  - Found $firstNum$secondNum\n";
        return $firstNum . $secondNum;
    }
    echo "  - Analysis failed\n";
    return 0;
}

function getBankAnalysis(string $bankData): array
{
    $analysis = [];
    foreach (str_split($bankData,1) as $pos => $joltage) {
        $joltage = (int)$joltage;
        if (!array_key_exists($joltage, $analysis)) {
            $analysis[$joltage] = [];
        }
        $analysis[$joltage][] = (int)$pos;
    }

    krsort($analysis);
    return $analysis;
}
