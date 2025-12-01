<?php

$input = [
    256,
    '97,167,54,178,2,11,209,174,119,248,254,0,255,1,64,190'
];

$test = [
    256,
//    '',
//    'AoC 2017',
//    '1,2,3',
    '1,2,4'
];

//$input = $test;

$sequence = range(0, $input[0] - 1, 1);
$mods = array_merge(unpack("C*", $input[1]), [17, 31, 73, 47, 23]);
$seqLen = count($sequence);

$skipSize = 0;
$curIndex = 0;
for ($i = 0; $i < 64; $i++) {
    foreach ($mods as $mod) {
        // Build start and end indexes of the slice we will be reversing.
        $hasOverflow = $curIndex + $mod >= $seqLen;
        $chunkDefs = [];
        $chunkDefs[] = [$curIndex, $hasOverflow ? $seqLen - $curIndex : $mod];
        // If overflow over end of sequence, loop back to start to get the rest
        if ($hasOverflow) {
            $chunkDefs[] = [0, ($curIndex + $mod) % $seqLen];
        }

        // Build substring to reverse.
        $slice = [];
        foreach ($chunkDefs as $chunkDef) {
            $slice = array_merge($slice, array_slice($sequence, $chunkDef[0], $chunkDef[1]));
        }

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
    }
}

$dense = [];
$chunks = array_chunk($sequence,  16);

foreach ($chunks as $chunk) {
    $working = 0;
    foreach ($chunk as $number) {
        $working = $working ^ $number;
    }

    $dense[] = $working;
}

foreach ($dense as $thing) {
    echo dechex($thing);
}
echo "\n\n";

