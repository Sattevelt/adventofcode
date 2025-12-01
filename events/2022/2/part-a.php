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

run(false);

function solvePuzzle(array $data): int
{
    $map = [
        'A' => 1,
        'B' => 2,
        'C' => 3,
        'X' => 1,
        'Y' => 2,
        'Z' => 3,
    ];
    $map2 = [
        'A' => [
            'X' => 3,
            'Y' => 6,
            'Z' => 0,
        ],
        'B' => [
            'X' => 0,
            'Y' => 3,
            'Z' => 6,
        ],
        'C' => [
            'X' => 6,
            'Y' => 0,
            'Z' => 3,
        ]
    ];

    $score = 0;
    foreach ($data as $game) {
        preg_match('/(A|B|C) (X|Y|Z)/', $game, $matches);

        $score += $map[$matches[2]];
        $score += $map2[$matches[1]][$matches[2]];
    }

    return $score;
}
