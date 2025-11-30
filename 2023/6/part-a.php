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
    $keys = [0 => 'times', 1 => 'records'];
    foreach ($data as $key => $line) {
        preg_match_all('/([0-9]*)/', substr($line, 9), $matches);
        foreach ($matches[0] as $match) {
            if ($match !== "") {
                ${$keys[$key]}[] = (int)$match;
            }
        }
    }


    $score = 1;
    foreach ($times as $key => $time) {
        $record = $records[$key];
        $beaten = 0;

        for ($try = 1; $try < $time; $try ++) {
            $dist = $try * ($time - $try);
            if ($dist > $record) {
                $beaten++;
            }
        }
        $score *= $beaten;
    }

    return $score;
}

