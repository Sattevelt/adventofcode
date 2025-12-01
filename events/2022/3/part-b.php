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
    $overlap = [];

    for ($i = 0; $i < count($data); $i = $i + 3) {
        $part1 = str_split($data[$i],1);
        $part2 = str_split($data[$i+1],1);
        $part3 = str_split($data[$i+2],1);

        foreach (array_unique(array_intersect($part1, $part2, $part3)) as $char) {
            $ord = ord($char);
            if ($ord < 91) {
                $overlap[] = $ord - 38;
            } else {
                $overlap[] = $ord - 96;
            }
        }
    }

    return array_sum($overlap);
}
