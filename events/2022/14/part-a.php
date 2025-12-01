<?php

function run(bool $runTests): void {
    if ($runTests) {
        $tests = require_once __DIR__ . DIRECTORY_SEPARATOR . 'tests.php';
        foreach ($tests as $testData) {
            $input = is_array($testData['input']) ? $testData['input'] : explode("\n", $testData['input']);
            $answer = solvePuzzle($input);
            $correct = $answer === $testData['solution'];
            echo sprintf("- %s: %s - %s\n", $testData['name'], $answer, $correct ? 'pass' : 'FAIL');
        }
    } else {
        $rawInput = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'input.txt');
        $inputLines = explode("\n", trim($rawInput, "\n"));

        echo sprintf("Solution: %s\n", solvePuzzle($inputLines));
    }
}

function solvePuzzle(array $data): int
{
    $maxY = -PHP_INT_MAX;
    $filled = [];
    foreach ($data as $line) {
        $coords = explode(' -> ', $line);
        $prevCoord = null;
        foreach ($coords as $coord) {
            $filled[$coord] = '#';
            $coord = explode(',', $coord);
            if ($coord[1] > $maxY) {
                $maxY = $coord[1];
            }
            if (! is_null($prevCoord)) {
                // Make a line from prev to cur.
                $startX = min($coord[0], $prevCoord[0]);
                $stopX = max($coord[0], $prevCoord[0]);
                $startY = min($coord[1], $prevCoord[1]);
                $stopY = max($coord[1], $prevCoord[1]);

                for ($y = $startY; $y <= $stopY; $y++) {
                    for ($x = $startX; $x <= $stopX; $x++) {
                        $filled[$x . ',' . $y] = '#';
                    }
                }
            }
            $prevCoord = $coord;
        }
    }

    return dropSand($filled, [500, 0], $maxY + 1);
}

function dropSand(array $filled, array $start, int $maxY): int
{
    $sandCount = 0;
    $dirs = [[0,1], [-1,1], [1,1]];

    while(true) {
        $curCoor = $start;

        if (array_key_exists(coor2str($curCoor), $filled)) {
            break; // We're done!
        }

        while (true) {
            if ($curCoor[1] < $maxY) {
                foreach ($dirs as $dir) {
                    $testCoor = [$curCoor[0] + $dir[0], $curCoor[1] + $dir[1]];
                    $testCoorStr = coor2str($testCoor);
                    if (!array_key_exists($testCoorStr, $filled)) {
                        $curCoor = $testCoor;
                        continue 2;
                    }
                }
            }
            $filled[coor2str($curCoor)] = 'o';
            $sandCount++;
            continue 2;
        }
    }

    return $sandCount;
}

function coor2str(array $coord): string
{
    return $coord[0] . ',' . $coord[1];
}

run(false);
