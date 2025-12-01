<?php

function run(bool $runTests): void {
    if ($runTests) {
        $tests = require_once __DIR__ . DIRECTORY_SEPARATOR . 'tests.php';
        foreach ($tests as $testData) {
            $answer = solvePuzzle($testData['input']);
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
    foreach ($data as $y => $line) {
        $data[$y] = str_split($line, 1);
    }

    $dirs = [
        [-1, 0],
        [1, 0],
        [0, 1],
        [0, -1],
    ];

    $numVisible = 0;
    foreach ($data as $y => $row) {
        foreach ($row as $x => $char) {
            foreach ($dirs as $dir) {
                $newX = $x + $dir[0];
                $newY = $y + $dir[1];

                $isVisible = true;
                while (isset($data[$newY][$newX])) {
                    if ($data[$newY][$newX] >= $char) {
                        $isVisible = false;
                        break;
                    }
                    $newX += $dir[0];
                    $newY += $dir[1];
                }

                if ($isVisible) {
                    $numVisible++;
                    break;
                }
            }
        }
    }

    return $numVisible;
}



function solveWorngPuzzle(array $data): int {
    $numVisible = 0;
    foreach ($data as $line) {
        $numVisible += getNumVisible(str_split($line, 1));
        $numVisible += getNumVisible(str_split(strrev($line), 1));
    }

    $rows = strlen($data[0]);
    $cols = count($data);
    $rotatedData = array_fill(0, $rows, array_fill(0, $cols, []));
    foreach ($data as $x => $line) {
        foreach (str_split($line, 1) as $y => $char) {
            $rotatedData[$y][$x] = $char;
        }
    }
    foreach ($rotatedData as $line) {
        $numVisible += getNumVisible($line);
        $numVisible += getNumVisible(array_reverse($line));
    }

    return $numVisible;
}

function getNumVisible(array $heights) {
    $curMax = -1;
    $numVisible = 0;
    foreach ($heights as $height) {
        if ($height > $curMax) {
            $numVisible++;
            $curMax = $height;
        }
    }

    return $numVisible;
}
