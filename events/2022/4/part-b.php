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
    $count = 0;
    $regex = '/^([0-9]+)-([0-9]+),([0-9]+)-([0-9]+)$/';
    foreach ($data as $line) {
        preg_match($regex, $line, $mts);
        if (
            ($mts[1] < $mts[3] && $mts[2] < $mts[3]) ||
            ($mts[3] < $mts[1] && $mts[4] < $mts[1])
        ) {
            continue;
        } else {
            $count++;
        }
    }
    return $count;
}
