<?php

$input = [
    256,
    [97,167,54,178,2,11,209,174,119,248,254,0,255,1,64,190]
];

$test = [
    5,
    [3, 4, 1, 5]
];

//$input = $test;

$sequence = range(0, $input[0] - 1, 1);
$mods = $input[1];

echo implode(' ', $sequence) . "\n\n";
$skipSize = 0;
$curIndex = 0;
$seqLen = count($sequence);
foreach ($mods as $mod) {
    // Build start and end indexes of the slice we will be reversing.
    $hasOverflow = $curIndex + $mod >= $seqLen;
    $chunkDefs = [];
    $chunkDefs[] = [$curIndex, $hasOverflow ? $seqLen - $curIndex : $mod];
    // If overflow over end of sequence, loop back to start to get the rest
    if ($hasOverflow) {
        $chunkDefs[] = [0, ($curIndex + $mod) % $seqLen];
    }

//    echo sprintf("***********************************
//curindex: %s
//skipsize: %s
//mod: %s
//",
//        $curIndex,
//        $skipSize,
//        $mod
//    );
//    var_dump($chunkDefs);

    // Build substring to reverse.
    $slice = [];
    foreach ($chunkDefs as $chunkDef) {
        $slice = array_merge($slice, array_slice($sequence, $chunkDef[0], $chunkDef[1]));
    }
//    echo implode(' ',$slice) . "\n";

    // Reverse string and place back into original string (chunked)
    $slice = array_reverse($slice);
    $sliceOffset = 0;
    foreach ($chunkDefs as $chunkDef) {
        array_splice(
            $sequence,
            $chunkDef[0],
            $chunkDef[1],
            array_slice(
                $slice,
                $sliceOffset,
                $chunkDef[1]
            )
        );
        $sliceOffset += $chunkDef[1];
    }

    // Increment curIndex and skipsize to new values
    $curIndex = ($curIndex + $mod + $skipSize) % $seqLen;
    $skipSize++;

    echo implode(' ', $sequence) . "\n\n";
}

echo sprintf("%s\n\n", $sequence[0] * $sequence[1]);

