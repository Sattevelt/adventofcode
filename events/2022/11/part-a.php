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
    $monkies = [];
    // Parse input
    while (count($data)) {
        $monkeyData = array_values(array_splice($data, 0, 7));
        $index = (int)substr($monkeyData[0], 7, 1);
        $items = explode(', ', substr($monkeyData[1], 18));
        $operationType = substr($monkeyData[2], 23, 1) == '*' ? Monkey::OP_TIMES : Monkey::OP_PLUS;
        $operationValue = substr($monkeyData[2], 25);
        if ($operationValue === 'old') {
            $operationType = Monkey::OP_SQUARE;
            $operationValue = 0;
        } else {
            $operationValue = (int)$operationValue;
        }
        $test = (int)substr($monkeyData[3], 21);
        $targets = [];
        $targets[1] = (int)substr($monkeyData[4], 29);
        $targets[0] = (int)substr($monkeyData[5], 30);

        $monkies[$index] = new Monkey($items, $operationType, $operationValue, $test, $targets);
    }

    $numRounds = 20;
    while($numRounds > 0) {
        /** @var Monkey $monkey */
        foreach ($monkies as $monkey) {
            $monkey->playRound($monkies);
        }

        $numRounds--;

//        echo "*********************************\nAfter round " . 20 - $numRounds . "\n";
//        foreach ($monkies as $index => $monkey) {
//            echo sprintf("Monkey %s: %s\n", $index, implode(', ', $monkey->reportItems()));
//        }
    }

    $inspections = [];
    foreach ($monkies as $monkey) {
        $inspections[] = $monkey->getInspectionCount();
    }
    rsort($inspections);


    return $inspections[0] * $inspections[1];
}

class Monkey
{
    public const OP_TIMES = 1;

    public const OP_PLUS = 2;
    public const OP_SQUARE = 3;

    private array $items = [];
    private int $operationType;
    private int $operationValue;
    private int $test;
    private array $target = [];
    private int $inspectionCount = 0;

    public function __construct(array $items, int $operationType, int $operationValue, int $test, array $target)
    {
        $this->items = $items;
        $this->operationType = $operationType;
        $this->operationValue = $operationValue;
        $this->test = $test;
        $this->target = $target;
    }

    public function receiveItem(int $item): void
    {
        $this->items[] = $item;
    }

    public function getInspectionCount(): int
    {
        return $this->inspectionCount;
    }

    public function reportItems(): array
    {
        return $this->items;
    }

    public function playRound(array $monkies): void
    {
        foreach ($this->items as $item) {
            // Inspect
            $this->inspectionCount++;
            if ($this->operationType == self::OP_PLUS) {
                $item += $this->operationValue;
            } elseif ($this->operationType === self::OP_TIMES) {
                $item *= $this->operationValue;
            } else {
                $item = $item * $item;
            }
            // Relief
            $item = floor($item / 3);

            // Test
            $target = ($item % $this->test === 0) ? $monkies[$this->target[1]] : $monkies[$this->target[0]];
            $target->receiveItem($item);
        }
        $this->items = [];
    }

}

run(false);
