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
    $hPos = [0,0];
    $tPos = [0,0];
    $dirs = [
        'R' => [1,0],
        'L' => [-1,0],
        'U' => [0,1],
        'D' => [0,-1],
    ];

    foreach ($data as $line) {
        list($dir, $dist) = explode(' ', $line);
        while ($dist > 0) {
            $hPos[0] += $dirs[$dir][0];
            $hPos[1] += $dirs[$dir][1];

            if (abs($hPos[0] - $tPos[0]) > 1 || abs($hPos[1] - $tPos[1]) > 1) {
                $tPos[0] += $hPos[0] <=> $tPos[0];
                $tPos[1] += $hPos[1] <=> $tPos[1];
                $posVisited["{$tPos[0]}|{$tPos[1]}"] = 0;
            }

            $dist--;
        }
        $bla = 1;
    }

    return count($posVisited);
}
