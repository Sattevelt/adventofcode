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
    $points = 0;
    foreach ($data as $line) {
        $cardPoints = 0;
        $line = str_replace('  ', ' ', $line);
        echo $line . PHP_EOL;
        preg_match('/Card *(\d)*: ((\d* ?)*) \| ((\d* ?)*)/', $line, $matches);
        $winning = explode(' ', $matches[2]);

        $card = explode(' ', $matches[4]);

        foreach ($winning as $winNo) {
            if (in_array($winNo, $card, true)) {
                if ($cardPoints === 0) {
                    $cardPoints = 1;
                } else {
                    $cardPoints *= 2;
                }
            }

        }
        $points += $cardPoints;
    }
    return $points;
}

