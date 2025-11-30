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
    $lists = [];
    foreach ($data as $line) {
        if ($line === '') {
            continue;
        }
        eval('$lists[] = ' . $line . ';');
    }

    $rightOrderIndexes = [];
    for ($i = 0; $i < count($lists); $i = $i + 2) {
        $index = ($i + 2) / 2;
        $result = compare($lists[$i], $lists[$i + 1]);

        echo "- $index: " . $result . "\n";

        if ($result === -1) {
            $rightOrderIndexes[] = $index;
        }
    }
    print_r($rightOrderIndexes);
    return array_sum($rightOrderIndexes);
}

function compare($val1, $val2): int
{
    $val1Int = is_int($val1);
    $val2Int = is_int($val2);
    if ($val1Int && $val2Int) {
        return $val1 <=> $val2;
    } elseif ($val1Int) {
        $val1 = [$val1];
    } elseif ($val2Int) {
        $val2 = [$val2];
    }
    // Both values are now lists
    $index = 0;
    do {
        $val1Sub = $val1[$index] ?? null;
        $val2Sub = $val2[$index] ?? null;

        if (is_null($val1Sub) && is_null($val2Sub)) {
            return 0;
        } elseif (is_null($val1Sub)) {
            return -1;
        } elseif(is_null($val2Sub)) {
            return 1;
        }
        $result = compare($val1Sub, $val2Sub);
        $index++;
    } while ($result === 0);

    return $result;
}

// 636 too low
// 5642 too high
// 11325 too high
run(false);
