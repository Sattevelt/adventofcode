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
    $max = 4000000;
    $sensors = [];
    // Sensor at x=2, y=18: closest beacon is at x=-2, y=15
    $regex = '/^Sensor at x=(-?[0-9]+), y=(-?[0-9]+): closest beacon is at x=(-?[0-9]+), y=(-?[0-9]+)$/';
    foreach ($data as $line) {
        preg_match($regex, $line, $mat);
        $sensor = new Sensor([(int)$mat[1], (int)$mat[2]], [(int)$mat[3],(int)$mat[4]]);
        $sensors[] = $sensor;
    }

    for ($y = 0; $y <= $max; $y++) {
        $ranges = getRangeForY($y, $sensors);

        foreach ($ranges as $i => $range) {
            $range[0] = max(0, $range[0]);
            $range[1] = min($max, $range[1]);
            $ranges[$i] = $range;
            if (count($ranges) > 1) {
                return ($ranges[0][1] + 1) * 4000000 + $y;
            }
        }
        if ($y % 1000 === 0) {
            echo "\ry: $y";
        }
    }

    return 1;
}

run(false);

function getRangeForY(int $y, array $sensors): array
{
    $ranges = [];
    foreach ($sensors as $sensor) {
        $range = $sensor->getCoverageOfY($y);
        if (count($range) === 2) {
            $ranges[] = $range;
        }
    }

    do {
        $ranges = array_unique($ranges, SORT_REGULAR);
        usort($ranges, function($a, $b) {
            if ($a[0] !== $b[0]) {
                return $a[0] <=> $b[0];
            } else {
                return $a[1] <=> $b[1];
            }
        });
        $rangeCount = count($ranges);
        $newRanges = [array_shift($ranges)];
        foreach ($ranges as $range) {
            $newRanges = mergeRanges($newRanges, $range);
        }
        $ranges = $newRanges;
    } while ($rangeCount > count($newRanges));

    return $ranges;
}

function mergeRanges(array $ranges, array $newRange): array
{
    $newRanges = [];
    foreach ($ranges as $range) {
        if ($newRange[0] <= $range[1] + 1) {
            $newRanges[] = [
                min($range[0], $range[1], $newRange[0], $newRange[1]),
                max($range[0], $range[1], $newRange[0], $newRange[1])
            ];
        } else {
            $newRanges[] = $range;
            $newRanges[] = $newRange;
        }
    }

    return $newRanges;
}

class Sensor
{
    private array $location;
    private array $beacon;

    public function __construct(array $location, array $beacon)
    {
        $this->location = $location;
        $this->beacon = $beacon;
    }

    public function getCoverageOfY(int $y)
    {
        $range = abs($this->location[0] - $this->beacon[0]) + abs($this->location[1] - $this->beacon[1]);
        $distToY = abs($this->location[1] - $y);
        $minMax = [];

        if ($distToY <= $range) {
            $offset = $range - $distToY;
            $minMax = [
                $this->location[0] - $offset,
                $this->location[0] + $offset
            ];
        }

        return $minMax;
    }
}

run(true);
