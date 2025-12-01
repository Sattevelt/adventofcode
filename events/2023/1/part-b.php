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
        $line = convertStringNumbers($line);
        $first = null;
        $last = null;
        foreach (str_split($line) as $char) {
            if (is_numeric($char)) {
                if (! $first) {
                    $first = (int)$char;
                }
                $last = (int)$char;
            }
        }
        $total += (int)((string)$first . (string)$last);
    }

    return $total;
}

function convertStringNumbers(string $line)
{
    $search = ['one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];
    $replace = ['1', '2', '3', '4', '5', '6', '7', '8', '9'];
    
    $min = 3;
    $max = 5;
    
    for ($st = 0; $st <= strlen($line) - $min ;$st++) {
        for($ln = $min; $ln <= $max; $ln++) {
            $substr = substr($line, $st, $ln);

            $result = array_search($substr, $search, true);
            if ($result !== false) {
                $replacement = $replace[$result];
                $line = substr_replace($line, $replacement, $st, 1);
                return convertStringNumbers($line);
            }
        }
    }
    return $line;
}
