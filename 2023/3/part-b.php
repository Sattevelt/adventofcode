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
    $gears = [];

    foreach ($grid as $y => $line) {
        $inNum = false;
        $curGear = null;
        $curNum = '';

        foreach ($line as $x => $char) {
            if (is_numeric($char)) {
                $curGear = $curGear ?? findGear($y, $x, $grid);
                $curNum .= $char;
                if (! $inNum) {
                    $inNum = true;
                }
            } else {
                if ($inNum && $curGear) {
                    $gears[$curGear][] = $curNum;
                }
                $curGear = null;
                $inNum = false;
                $curNum = '';
            }
        }
        if ($inNum && $curGear) {
            $gears[$curGear][] = $curNum;
        }
    }

    foreach ($gears as $gear) {
        if (count($gear) === 2) {
            $total += $gear[0] * $gear[1];
        }
    }

    return $total;
}

function findGear($y, $x, $grid) {
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

    foreach ($dirs as $dir) {
        $part = ($grid[$y + $dir[1]][$x + $dir[0]] ?? '0') === '*';
        if ($part) {
            return $x + $dir[0] . "|" . $y + $dir[1];
        }
    }
    return null;
}

