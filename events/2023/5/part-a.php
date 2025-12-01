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
    $seeds = [];
    $curMapper = null;
    $maps = [];

    for ($i = 0; $i < count($data); $i++) {
        $line = $data[$i];
        if ($i === 0) {
            preg_match('/seeds\: ([\d ]*)/', $line, $matches);
            $seeds = explode(' ', $matches[1]);
        } elseif ($data[$i] === '') {
            $i++;
            $curMapper = new Mapper();
            $maps[] = $curMapper;
        } else {
            $mapData = explode(' ', $line);
            $curMapper->addMapping($mapData[1], $mapData[0], $mapData[2]);
        }
    }

//    for ($i = 1; $i <= 100; $i++) {
//        $target = $maps[0]->getDestValue($i);
//        echo sprintf('%s | %s' . PHP_EOL, $i, $target);
//    }
    $lowest = PHP_INT_MAX;
    foreach ($seeds as $seed) {
        echo $seed;
        foreach ($maps as $map) {
            $seed = $map->getDestValue($seed);
            echo ' -> ' . $seed;
        }
        echo PHP_EOL;
        if ($seed < $lowest) {
            $lowest = $seed;
        }
    }

    return $lowest;
}

class Mapper
{
    private array $mappings = [];

    public function addMapping(int $sourceStart, int $destStart, int $range)
    {
        $this->mappings[] = [
            'sourceStart' => $sourceStart,
            'destStart' => $destStart,
            'range' => $range,
            'modifier' => $destStart - $sourceStart,
        ];
    }

    public function getDestValue(int $value)
    {
        foreach ($this->mappings as $mapping) {
            // Is in range?
            if ($value >= $mapping['sourceStart'] && $value < $mapping['sourceStart'] + $mapping['range']) {
                return $value + $mapping['modifier'];
            }
        }
        return $value;
    }
}
