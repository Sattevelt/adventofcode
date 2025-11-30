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
    echo "\n";
    $x = 1;
    $stack = [];
    $minCycles = count($data);
    $curCycle = 0;

    while ($curCycle < $minCycles || count($stack)) {
        // Parse line
        if (isset($data[$curCycle])) {
            preg_match('/(noop|addx) ?(-?[0-9]*)?/', $data[$curCycle], $matches);

            if ($matches[1] === 'addx') {
                $stack[] = 'noop';
                $stack[] = $matches[2];
            } else {
                $stack[] = 'noop';
            }
        }

        // Draw pixel
        if (in_array($curCycle,[40,80,120,160,200,240])) {
            echo "\n";
        }
        $rowIndex = $curCycle % 40;
        echo ($rowIndex -1 <= $x && $rowIndex + 1 >= $x) ? '#' : '.';



        // Execute instruction
        $instr = array_shift($stack);
        if ($instr !== 'noop') {
            $x += $instr;
        }

        $curCycle++;
    }

    return 1;
}

run(false);
