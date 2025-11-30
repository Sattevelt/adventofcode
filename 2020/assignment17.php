<?php

$data = [
    '##..#.#.',
    '###.#.##',
    '..###..#',
    '.#....##',
    '.#..####',
    '#####...',
    '#######.',
    '#.##.#.#'
];

//$data = [
//    '.#.',
//    '..#',
//    '###'
//];

$cycle = 1;
echo "Parse and expand...";
$state = parseAndExpandData($data, 6);
echo "Done\n";

//print_r(array_slice($state[0], $cycles, 3, true));
while ($cycle <= 6) {
    echo "Running cyle $cycle ";
    $copyState = $state;
    foreach ($state as $w => $zState) {
        foreach ($zState as $z => $plane) {
            foreach ($plane as $y => $row) {
                foreach ($row as $x => $cube) {
                    $numNeighbours = getNumNeighbourCubes($state, $x, $y, $z, $w);
                    $newValue = $cube;
                    if ($cube === '#' && !in_array($numNeighbours, [2, 3])) {
                        $newValue = '.';
                    } elseif ($cube === '.' && $numNeighbours === 3) {
                        $newValue = '#';
                    }
                    $copyState[$w][$z][$y][$x] = $newValue;
                }
            }
        }
        echo ".";
    }
    $state = $copyState;
    $cycle++;
    echo " Done\n";
}
echo "Counting cubes... ";
$cubeCount = 0;
foreach ($state as $zPlane) {
    foreach ($zPlane as $plane) {
        foreach ($plane as $row) {
            foreach ($row as $cube) {
                if ($cube === '#') {
                    $cubeCount++;
                }
            }
        }
    }
}
echo "$cubeCount\n";
//print_r(array_slice($state[0], $cycles, 3, true));

function getNumNeighbourCubes(array $data, int $x, int $y, int $z, int $w) {
    $count = 0;
    $xMods = [0, 1, -1];
    $yMods = $xMods;
    $zMods = $xMods;
    $wMods = $xMods;

    foreach ($wMods as $wMod) {
        foreach ($zMods as $zMod) {
            foreach ($yMods as $yMod) {
                foreach ($xMods as $xMod) {
                    if ($xMod === 0 && $yMod === 0 && $zMod === 0 && $wMod === 0) {
                        continue;
                    }
                    $newX = $x + $xMod;
                    $newY = $y + $yMod;
                    $newZ = $z + $zMod;
                    $newW = $w + $wMod;

                    if (isset($data[$newW][$newZ][$newY][$newX]) && $data[$newW][$newZ][$newY][$newX] === '#') {
                        $count++;
                    }
                }
            }
        }
    }
//    if ($count > 0) {
//        echo "found $count neighbors for $z, $y, $x\n";
//    }
    return $count;
}

function parseAndExpandData(array $data, $expansion) {
    $size = count(str_split($data[0]));
    $row = myArrayFill(0 - $expansion, $size + (2 * $expansion), '.');
    $col = myArrayFill(0 - $expansion, $size + (2 * $expansion), $row);
    $zState = myArrayFill(0 - $expansion, 1 + (2 * $expansion), $col);
    $allState = myArrayFill(0 - $expansion, 1 + (2 * $expansion), $zState);

    $z = 0;
    $w = 0;
    foreach ($data as $y => $line) {
        foreach (str_split($line) as $x => $value) {
            $allState[$w][$z][$y][$x] = $value;
        }
    }

    return $allState;
}

function myArrayFill(int $startIndex, int $size, $value) {
    $curIndex = $startIndex;
    $newArray = [];
    for ($i = 0; $i < $size; $i++) {
        $newArray[$curIndex] = $value;
        $curIndex++;
    }
    return $newArray;
}