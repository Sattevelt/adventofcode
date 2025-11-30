<?php

//$data = '219748365';

$data = '389125467';

echo "Building deck\n";
$cups = str_split($data);
for ($i = 10; $i <= 1000000; $i++) {
    $cups[] = $i;
}

$dataSize = count($cups);
$pickSize = 3;
$currentLabel = reset($cups);

echo "Staring iterations:\n";

for ($i = 0; $i < 10000000; $i++) {
//    echo "**********************\n";
//    foreach ($cups as $cup) {
//        if ($currentLabel == $cup) {
//            echo "($cup) ";
//        } else {
//            echo "$cup ";
//        }
//    }
//    echo "\n";
//    echo "searching 1\n";
    $curIndex = array_search($currentLabel, $cups);

    // Get picks from cups.
    $pickIndex = ($curIndex + 1) % $dataSize;
//    echo "splicing 1\n";
    $picks = array_splice($cups, $pickIndex, $pickSize);
    if (count($picks) < 3) {
//        echo "splicing 2 + merging 1\n";
        $picks = array_merge($picks, array_splice($cups, 0, $pickSize - count($picks)));
    }

    // Determine destination cup
    $destinationLabel = $currentLabel - 1;
//    echo "in array until found\n";
    while (!in_array($destinationLabel, $cups)) {
//        echo "\r$destinationLabel                     ";
        $destinationLabel--;
        if ($destinationLabel < 1) {
            $destinationLabel = max($cups);
        }
    }
//    echo "searcging 2\n";
    $destinationIndex = array_search($destinationLabel, $cups); // Find matching index

    // Replace picks after current cup
    $insertIndex = ($destinationIndex + 1) % $dataSize;
//    echo "splicing 2\n";
    array_splice($cups, $insertIndex, 0, $picks);

    // Determine new 'current cup'
//    echo "searching 3\n";
    $curIndex = (array_search($currentLabel, $cups) + 1) % $dataSize;
    $currentLabel = $cups[$curIndex];
    if ($i % 10 === 0) {
        echo "\riteration $i/10000000 done!";
    }
}

$cup1AtIndex = array_search(1, $cups);
echo "\n\nfirst star at: " . $cups[$cup1AtIndex + 1] . "\n";
echo "second star at: " . $cups[$cup1AtIndex + 2] . "\n";
echo "product: " . $cups[$cup1AtIndex + 2] * $cups[$cup1AtIndex + 2] . "\n";
//$final = array_splice($cups, array_search(1, $cups) + 1, 10);
//$final = array_merge($final, array_splice($cups, 0, -1));
//print_r($final);
//echo implode('', $final) . "\n";



