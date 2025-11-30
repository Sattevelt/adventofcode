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
    list($input, $output) = readInput($data);
    $sizes = [];
    $total = findSize($output, $sizes);

    return array_sum($sizes);
}

function findSize(array $input, array &$sizes): int
{
    $size = 0;
    foreach ($input as $item) {
        if (is_array($item)) {
            $size += findSize($item, $sizes);
        } else {
            $size += (int)$item;
        }
    }
    if ($size <= 100000) {
        $sizes[] = $size;
    }
    return $size;
}

function readInput(array $input): array
{
    $output = [];
    $cmdRegex = '/^\$ (cd|ls)( (.*))?$/';
    $itemRegex = '/^(dir|[0-9]+) (.*)$/';

    while (count($input)) {
        $line = array_shift($input);
        if (preg_match($cmdRegex, $line, $matches) === 1) {
            if ($matches[1] === 'cd') {
                if ($matches[3] === '..') {
                    return [$input, $output];
                } else {
                    list($input, $outputSub) = readInput($input);
                    $output[$matches[3]] = $outputSub;
                }
            }
        } elseif (preg_match($itemRegex, $line, $matches) === 1) {
            if ($matches[1] === 'dir') {
                $output[$matches[2]] = [];
            } else {
                $output[] = $matches[1];
            }
        } else {
            throw new Exception("Line '{$line}' does not match.");
        }
    }
    return [$input, $output];
}



