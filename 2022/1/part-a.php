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
    $maxTotal = 0;
    $runningTotal = 0;
    foreach ($data as $value) {
        if ((string)$value == '') {
            $runningTotal = 0;
        } else {
            $runningTotal += $value;
        }

        if ($runningTotal > $maxTotal) {
            $maxTotal = $runningTotal;
        }
    }
    return $maxTotal;
}
