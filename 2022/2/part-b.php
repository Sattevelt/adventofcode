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

run(true);

function solvePuzzle(array $data): int
{
    $map = [
        'A' => 1,
        'B' => 2,
        'C' => 3,
        'X' => 0,
        'Y' => 3,
        'Z' => 6,
    ];
    $map2 = [
        'A' => [
            'X' => 'C',
            'Y' => 'A',
            'Z' => 'B',
        ],
        'B' => [
            'X' => 'A',
            'Y' => 'B',
            'Z' => 'C',
        ],
        'C' => [
            'X' => 'B',
            'Y' => 'C',
            'Z' => 'A',
        ]
    ];

    $score = 0;
    foreach ($data as $game) {
        preg_match('/(A|B|C) (X|Y|Z)/', $game, $matches);

        $score += $map[$matches[2]];
        $score += $map[$map2[$matches[1]][$matches[2]]];
    }

    return $score;
}