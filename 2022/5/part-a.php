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

function solvePuzzle(array $data): string
{
    $curLine = 0;
    $regex = '/^\[([A-Z])\]/';
    $stacks = array_fill(1,(strlen($data[0]) + 1) / 4, []);
    foreach ($data as $line) {
        if (substr($line, 0, 2) === ' 1') {
            $data = array_slice($data, $curLine + 2);
            break;
        }
        $curCol = 0;
        $checkString = substr($line, $curCol * 4, $curCol * 4 + 4);
        while($checkString !== '') {
            $curCol++;
            if (preg_match($regex, $checkString, $matches) === 1) {
                $stacks[$curCol][] = $matches[1];
            }

            $checkString = substr($line, $curCol * 4, $curCol * 4 + 4);
        }

        $curLine++;
    }

    $regex = '/^move ([0-9]+) from ([0-9]+) to ([0-9]+)$/';
    foreach ($data as $line) {
        preg_match($regex, $line, $mat);
        $qty = $mat[1];
        $src = $mat[2];
        $dest = $mat[3];

        $chunk = array_splice($stacks[$src], 0, $qty);
        foreach ($chunk as $value) {
            array_unshift($stacks[$dest], $value);
        }
    }

    $output = '';
    foreach ($stacks as $stack) {
        if (count($stack) > 0) {
            $output .= $stack[0];
        }
    }

    return $output;
}
