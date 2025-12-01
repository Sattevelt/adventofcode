<?php

$input = "hwlqcszp";

$test = "flqrgnkx";

$input = $test;

$grid = [];
for ($i = 0; $i < 128; $i++) {
    $hexData = getKnotHash(32, $input . '-' . $i, 64);
    $bin = '';
    foreach ($hexData as $hexDat) {
        $charBin = str_pad(base_convert($hexDat, 16, 2), 4, 0, STR_PAD_LEFT);
        if (strlen($charBin) !== 4) {
            throw new Exception(sprintf("NON_well formed bin string found: %s, based on hex string: %s", $charBin, $hexDat));
        }
        $bin .= $charBin;
    }
    $grid[] = $bin;
    echo "$bin\n";
}

//print_r($grid);




function getKnotHash($size, $salt, $iterations)
{
    $sequence = range(0, $size - 1, 1);
    $mods = array_merge(unpack("C*", $salt), [17, 31, 73, 47, 23]);
    $seqLen = count($sequence);

    $skipSize = 0;
    $curIndex = 0;
    for ($i = 0; $i < $iterations; $i++) {
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
    $chunks = array_chunk($sequence, 16);

    foreach ($chunks as $chunk) {
        $working = 0;
        foreach ($chunk as $number) {
            $working = $working ^ $number;
        }

        $dense[] = $working;
    }

    $hexData = [];
    foreach ($dense as $thing) {
        $hexData[] = dechex($thing);
    }
    return $hexData;
}

