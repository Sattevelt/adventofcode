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
    $line = $data[0];
    $offset = 0;
    $markerLength = 14;

    while (true) {
        $substr = substr($line, $offset, $markerLength);
        $letters = [];

        foreach (str_split($substr, 1) as $letter) {
            $letters[$letter] = 0;
        }
        if (count($letters) >= $markerLength) {
            break;
        }
        $offset++;
    }

    return $offset + $markerLength;
}
