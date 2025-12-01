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
    $time = '';
    $record = '';
    foreach ($data as $key => $line) {
        foreach (str_split($line) as $char) {
            if (is_numeric($char)) {
                if ($key === 0) {
                    $time .= $char;
                } else {
                    $record .= $char;
                }
            }
        }
    }
    $time = (int)$time;
    $record = (int)$record;

    $score = 0;
    echo 'fdsfds';
    for ($try = 1; $try < $time; $try ++) {
        $dist = $try * ($time - $try);
        if ($dist > $record) {
            $score++;
        }
        if ($try % 10000 === 0) {
            echo "\r" . sprintf('%s/%s', $try, $time);
        }
    }
echo PHP_EOL;
    return $score;
}

