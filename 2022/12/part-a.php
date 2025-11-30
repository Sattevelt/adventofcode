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
    $grid = [];
    $starts = [];
    foreach ($data as $y => $line) {
        $row = str_split($line, 1);
        foreach ($row as $x => $char) {
            if ($char === 'S') {
                $char = 'a';
            } elseif ($char === 'E') {
                $end = [$y, $x];
                $char = 'z';
            }
            if ($char === 'a' && $x === 0) {
                $starts[] = [$y, $x];
            }
            $grid[$y][$x] = ord($char) - 97;
        }
    }

    echo sprintf("Checking %s starts\n", count($starts));
    $lengths = [];
    foreach ($starts as $i => $start) {
        echo sprintf("- %s: %s: ", $i, pos2str($start));
        $length = getPathLength($grid, $start, $end);
        $lengths[] = $length;

        echo "$length\n";
    }
    sort($lengths);

    return $lengths[0];
}

function getPathLength(array $grid, array $start, array $end): ?int
{
    /** @var Node[] $open */
    $open = [new Node($start, 0, guessDistance($start, $end))];
    /** @var  $closed */
    $closed = [];
    $endStr = pos2str($end);
    $dirs = [
        [1,0],
        [-1,0],
        [0,1],
        [0,-1],
    ];

    while(true) {
        if (count($open) === 0) {
            return PHP_INT_MAX;
        }
        // Get best possible
        uasort($open, 'sortNodes');
        $curNode = array_shift($open);
        $curPos = $curNode->getPos();

        // Move to visited
        $closed[$curNode->getPosStr()] = $curNode;
        if ($curNode->getPosStr() === $endStr) {
            break;
        }

        foreach ($dirs as $dir) {
            $candPos = [$curPos[0] + $dir[0], $curPos[1] + $dir[1]];
            $candStr = pos2str($candPos);

            // Already visited, off-grid, or move not allowed?
            if (array_key_exists($candStr, $closed) ||
                ! isset($grid[$candPos[0]][$candPos[1]]) ||
                $grid[$candPos[0]][$candPos[1]] - $grid[$curPos[0]][$curPos[1]] > 1) {
                continue;
            }

            if (array_key_exists($candStr, $open)) {
                $candNode = $open[$candStr];
                if ($candNode->getG() > $curNode->getG() + 1) {
                    $candNode->setG($curNode->getG() + 1);
                    $candNode->setParent($curNode);
                }
            } else {
                $open[$candStr] = new Node($candPos, $curNode->getG() + 1, guessDistance($candPos, $end), $curNode);
            }
        }
    }

    return $curNode->getG();
}

function pos2str(array $pos): string
{
    return $pos[0] . '|' . $pos[1];
}

function guessDistance(array $pos1, array $pos2) {
    return abs($pos1[0] - $pos2[0]) + abs($pos1[1] - $pos2[1]);
}

function sortNodes(Node $node1, Node $node2): int
{
    return $node1->getF() <=> $node2->getF();
}

class Node
{
    private int $g; // Dist to start node (curScore)
    private int $h; // Expected score to target node
    private array $pos;
    private string $posStr;
    private ?Node $parent;

    public function __construct(array $pos, int $g, int $h, ?Node $parent = null)
    {
        $this->pos = $pos;
        $this->posStr = $pos[0] . '|' . $pos[1];
        $this->g = $g;
        $this->h = $h;
        $this->parent = $parent;
    }

    /**
     * @return int
     */
    public function getG(): int
    {
        return $this->g;
    }

    /**
     * @param int $g
     */
    public function setG(int $g): void
    {
        $this->g = $g;
    }

    /**
     * @return int
     */
    public function getH(): int
    {
        return $this->h;
    }

    /**
     * @return int
     */
    public function getF(): int
    {
        return $this->h + $this->g;
    }

    /**
     * @return array
     */
    public function getPos(): array
    {
        return $this->pos;
    }

    /**
     * @return string
     */
    public function getPosStr(): string
    {
        return $this->posStr;
    }

    /**
     * @return Node|null
     */
    public function getParent(): ?Node
    {
        return $this->parent;
    }

    /**
     * @param Node|null $parent
     */
    public function setParent(?Node $parent): void
    {
        $this->parent = $parent;
    }
}

run(false);
