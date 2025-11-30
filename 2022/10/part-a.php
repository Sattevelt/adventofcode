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
    $x = 1;
    $signals = [];
    $stack = [];
    $minCycles = count($data);
    $curCycle = 1;

    while ($curCycle <= $minCycles || count($stack)) {
        // Parse line
        if (isset($data[$curCycle - 1])) {
            preg_match('/(noop|addx) ?(-?[0-9]*)?/', $data[$curCycle - 1], $matches);

            if ($matches[1] === 'addx') {
                $stack[] = 'noop';
                $stack[] = $matches[2];
            } else {
                $stack[] = 'noop';
            }
        }

        // Check value
        if (in_array($curCycle, [20,60,100,140,180,220])) {
            $signals[$curCycle] = $curCycle * $x;
        }
//        echo "$curCycle: $x\n";

        // Execute instruction
        $instr = array_shift($stack);
        if ($instr !== 'noop') {
            $x += $instr;
        }

        $curCycle++;
    }
    print_r($signals);

    return array_sum($signals);
}

run(false);
