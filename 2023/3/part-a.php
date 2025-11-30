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
    $grid = [];
    foreach ($data as $line) {
        $grid[] = str_split($line);
    }
    $total = 0;

    foreach ($grid as $y => $line) {
        $inNum = false;
        $isPart = false;
        $curNum = '';

        foreach ($line as $x => $char) {
            if (is_numeric($char)) {
                $isPart = $isPart || hasAdjacentPart($y, $x, $grid);
                $curNum .= $char;
                if (! $inNum) {
                    $inNum = true;
                }
            } else {
                if ($inNum && $isPart) {
                    $total += (int)$curNum;
                }
                $isPart = false;
                $inNum = false;
                $curNum = '';
            }
        }
        if ($inNum && $isPart) {
            $total += (int)$curNum;
        }
    }
    return $total;
}

function hasAdjacentPart($y, $x, $grid) {
    $dirs = [
        [-1,-1],
        [-1,0],
        [-1,1],
        [0,-1],
        [0,1],
        [1,-1],
        [1,0],
        [1,1]
    ];
    $invalids = ['.', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0'];

    foreach ($dirs as $dir) {
        $part = !in_array(($grid[$y + $dir[1]][$x + $dir[0]] ?? '0'), $invalids);
        if ($part) {
            return true;
        }
    }
    return false;
}

