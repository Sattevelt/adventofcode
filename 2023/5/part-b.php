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
    /** @var Mapper[] $maps */
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

//    for ($j = 0; $j < 101; $j++) {
//        echo $j . ' -> ';
//        foreach ($maps as $map) {
//            $j = $map->getDestValue($j);
//        }
//        for ($i = count($maps) - 1; $i >= 0; $i--) {
//            $map = $maps[$i];
//            $j = $map->getSourceValue($j);
//        }
//        echo $j . PHP_EOL;
//    }
//return 0;


    $lowest = PHP_INT_MAX;
    for ($i = 0; $i < count($seeds); $i += 2) {
        $start = $seeds[$i];
        $range = $seeds[$i + 1];
        $max = $start + $range - 1;
        $curValue = $start;

        while ($curValue <= $max) {
            // Get score for $curValue
            $seed = $curValue;
            foreach ($maps as $map) {
                $seed = $map->getDestValue($seed);
            }
            if ($seed < $lowest) {
                $lowest = $seed;
            }

            // What is the next value to calc after a split
            $workingValue = $curValue;
            $maxRange = $range;
            foreach ($maps as $map) {
                $maxRange = $map->getRangeToNextSplit($workingValue, $maxRange);
                $workingValue = $map->getDestValue($workingValue);
            }
            $curValue += $maxRange + 1;
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
            'sourceEnd' => $sourceStart + $range - 1,
            'destStart' => $destStart,
            'destEnd' => $destStart + $range - 1,
            'range' => $range,
            'modifier' => $destStart - $sourceStart,
        ];
        $this->sortMapping();
    }

    public function getDestValue(int $value)
    {
        foreach ($this->mappings as $mapping) {
            // Is in range?
            if ($value >= $mapping['sourceStart'] && $value <= $mapping['sourceEnd']) {
                return $value + $mapping['modifier'];
            }
        }
        return $value;
    }

    public function getSourceValue(int $value)
    {
        foreach ($this->mappings as $mapping) {
            // Is in range?
            if ($value >= $mapping['destStart'] && $value <= $mapping['destEnd']) {
                return $value - $mapping['modifier'];
            }
        }
        return $value;
    }

    public function getRangeToNextSplit($value, $max)
    {
        foreach ($this->mappings as $mapping) {
            // Is in range?
            if ($value >= $mapping['sourceStart'] && $value <= $mapping['sourceEnd']) {
                return min($max, $mapping['sourceEnd'] - $value);
            }
        }

        if ($value < $this->mappings[0]['sourceStart']) {
            return min($max, $this->mappings[0]['sourceStart'] - $value);
        }

        return $max;
    }

    private function sortMapping()
    {
        usort(
            $this->mappings,
            function ($a, $b) {
                return $a['sourceStart'] <=> $b['sourceStart'];
            }
        );
    }
}
