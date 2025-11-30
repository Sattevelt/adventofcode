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
    $instr = str_split($data[0]);
    $network = [];
    unset($data[1]);unset($data[0]);
    foreach($data as $line) {
        preg_match('/([A-Z]{3}) = \(([A-Z]{3}), ([A-Z]{3})\)/', $line, $matches);
        $network[$matches[1]] = [
            'L' => $matches[2],
            'R' => $matches[3]
        ];
    }

    $curNode = 'AAA';
    $curInst = 0;
    $numSteps = 0;
    while ($curNode !== 'ZZZ') {
        $curNode = $network[$curNode][$instr[$curInst]];
        $numSteps++;
        $curInst++;
        if ($curInst === count($instr)) {
            $curInst = 0;
        }
    }

    return $numSteps;
}

