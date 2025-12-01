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
    $posVisited = ['0|0' => 0];
    $headPos = [0,0];
    $knots = array_fill(0, 9, [0,0]);
    $dirs = [
        'R' => [1,0],
        'L' => [-1,0],
        'U' => [0,1],
        'D' => [0,-1],
    ];

    foreach ($data as $line) {
        list($dir, $dist) = explode(' ', $line);
        while ($dist > 0) {
            $headPos[0] += $dirs[$dir][0];
            $headPos[1] += $dirs[$dir][1];

            $prevKnot = $headPos;
            foreach($knots as $index => $knot) {
                if (abs($prevKnot[0] - $knot[0]) > 1 || abs($prevKnot[1] - $knot[1]) > 1) {
                    $knots[$index][0] += $prevKnot[0] <=> $knot[0];
                    $knots[$index][1] += $prevKnot[1] <=> $knot[1];

                    if ($index === 8) {
                        $posVisited["{$knots[$index][0]}|{$knots[$index][1]}"] = 0;
                    }
                }
                $prevKnot = $knots[$index];
            }

            $dist--;
        }
    }

    return count($posVisited);
}
