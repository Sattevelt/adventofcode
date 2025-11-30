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

const UP = 0;
const RIGHT = 1;
const DOWN = 2;
const LEFT = 3;


function solvePuzzle(array $data): int
{
    $sizeX = strlen($data[0]);
    $sizeY = count($data);
    $objects = [];
    $visited = [];
    $curX = null;
    $curY = null;
    $dir = null;
    $offsets = [];

    foreach ($data as $y => $row) {
        foreach (str_split($row) as $x => $char) {
            if ($char === '.') {
                continue;
            } elseif ($char === '#') {
                $objects[$x][] = $y;
            } else {
                $curX = $x;
                $curY = $y;
                $dir = match ($char) {
                    '^' => UP,
                    '>' => RIGHT,
                    'v' => DOWN,
                    '<' => LEFT
                };
                $offsets = getDirOffsets($dir);
            }
        }
    }

    while (stillOnMap($sizeX, $sizeY, $curX, $curY)) {
        $nextX = $curX + $offsets[0];
        $nextY = $curY + $offsets[1];
        if (in_array($nextY, $objects[$nextX] ?? [], true)) {
            echo sprintf("Hit object at (%s, %s), rotating right.\n", $nextX, $nextY);
            $dir = turnRightFromDir($dir);
            $offsets = getDirOffsets($dir);
            continue;
        }
        $curX = $nextX;
        $curY = $nextY;
        $visited[$curX . '|' . $curY] = 1;
    }
    echo sprintf("Exited map at (%s, %s)\n", $curX, $curY);

    return count($visited);
}

function turnRightFromDir(int $dir) {
    return ($dir + 1) % 4;
}
function getDirOffsets(int $dir) {
    $dirOffsets = [
        UP => [0, -1],
        RIGHT => [1, 0],
        DOWN => [0, 1],
        LEFT => [-1, 0]
    ];
    return $dirOffsets[$dir];
}

function stillOnMap($sizeX, $sizeY, $posX, $posY) {
    return (
        $posX >= 0
        && $posX < $sizeX
        && $posY >= 0
        && $posY < $sizeY
    );
}

