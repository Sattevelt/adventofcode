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
    $totals = [];
    $runningTotal = 0;
    foreach ($data as $value) {
        if ((string)$value == '') {
            $totals[] = $runningTotal;
            $runningTotal = 0;
        } else {
            $runningTotal += $value;
        }
    }
    $totals[] = $runningTotal;

    rsort($totals);

    return array_sum(array_slice($totals, 0, 3));
}
