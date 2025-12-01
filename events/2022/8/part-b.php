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

    $maxScore = 0;
    foreach ($data as $y => $row) {
        foreach ($row as $x => $char) {

            $curScore = 1;
            foreach ($dirs as $dir) {
                $dirScore = 0;
                $newX = $x + $dir[0];
                $newY = $y + $dir[1];

                while (isset($data[$newY][$newX])) {
                    $dirScore++;
                    if ($data[$newY][$newX] >= $char) {
                        break;
                    }
                    $newX += $dir[0];
                    $newY += $dir[1];
                }

                $curScore *= $dirScore;
            }

            if ($curScore > $maxScore) {
                $maxScore = $curScore;
            }
        }
    }

    return $maxScore;
}
