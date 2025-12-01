<?php

$input = "11	11	13	7	0	15	5	5	4	4	1	1	7	1	15	11";
$test = "0	2	7	0";

$banks = explode("\t", $input);
$banksStr = implode('|', $banks);
$storedBanks = [];
$passes = 0;

while (! in_array($banksStr, $storedBanks)) {
    $storedBanks[] = $banksStr;

    $banks = distribute($banks);
    $banksStr = implode('|', $banks);

    $passes++;
}

$findStr = $banksStr;
$storedBanks = [];
$passes = 0;
while (! in_array($banksStr, $storedBanks)) {
    $storedBanks[] = $banksStr;

    $banks = distribute($banks);
    $banksStr = implode('|', $banks);

    $passes++;
}



echo "$passes\n\n";

function distribute($banks)
{
    $maxIndex = 0;
    foreach ($banks as $index => $bank) {
        if ($bank > $banks[$maxIndex]) {
            $maxIndex = $index;
        }
    }

    $add = (int) ($banks[$maxIndex] / count($banks));
    $remain = $banks[$maxIndex] % count($banks);

    echo sprintf("%s (%s,%s)\n", $banksStr = implode('|', $banks), $add, $remain);

    $banks[$maxIndex] = 0;
    foreach ($banks as $index => $bank) {
        $banks[$index] += $add;
    }
    for ($i = $maxIndex + 1; $i <= $maxIndex + $remain; $i++) {
        $banks[$i % count($banks)]++;
    }

    return $banks;
}
