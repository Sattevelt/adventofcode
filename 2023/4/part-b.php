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
    $cardCopies = [];
    for ($i = 1; $i <= count($data); $i++) {
        $cardCopies[$i] = 1;
    }
    foreach ($data as $line) {
        $line = preg_replace('/ {2,}/', ' ', $line);
        $matching = 0;

        preg_match('/Card *(\d*): ((\d* ?)*) \| ((\d* ?)*)/', $line, $matches);
        $cardNo = (int)$matches[1];
        $winning = explode(' ', $matches[2]);
        $card = explode(' ', $matches[4]);

        foreach ($winning as $winNo) {
            if (trim($winNo) === '') {
                continue;
            }
            if (in_array($winNo, $card, true)) {
                $matching++;
            }
        }

        $factor = $cardCopies[$cardNo];
        for ($i = $cardNo + 1; $i <= $cardNo + $matching; $i++) {
            $cardCopies[$i] = $cardCopies[$i] + $factor;
        }
    }
    return array_sum($cardCopies);
}
