<?php

function run(bool $runTests): void {
    if ($runTests) {
        $tests = require_once __DIR__ . DIRECTORY_SEPARATOR . 'tests.php';
        foreach ($tests as $testData) {
            $input = is_array($testData['input']) ? $testData['input'] : explode("\n", $testData['input']);
            $answer = solvePuzzle($input);
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
    $total = 0;
    foreach ($data as $line) {
        $minima = [
            'red' => PHP_INT_MIN,
            'blue' => PHP_INT_MIN,
            'green' => PHP_INT_MIN,
        ];

        preg_match('/Game ([0-9]{1,3}): (.*)/', $line, $matches);
        $id = $matches[1];
        $pulls = explode('; ', $matches[2]);
        foreach ($pulls as $pull) {
            $split = explode(', ', $pull);
            foreach ($split as $sngl) {
                preg_match('/([0-9]*) (red|blue|green)/', $sngl, $matches);
                $blocks = (int)$matches[1];
                $color = $matches[2];
                if ($minima[$color] < $blocks) {
                    $minima[$color] = $blocks;
                }
            }
        }

        $total += $minima['red'] * $minima['green'] * $minima['blue'];
    }
    return $total;
}

