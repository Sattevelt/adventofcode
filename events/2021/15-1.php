<?php

declare(strict_types=1);

require_once('common.php');
$input = getPuzzleInput(15);
$input = explode("\r\n", $input);
//$input = explode("\n", "1163751742
//1381373672
//2136511328
//3694931569
//7463417111
//1319128137
//1359912421
//3125421639
//1293138521
//2311944581");

//$input = explode("\n", "11111
//99991
//11111
//19939
//11111");

$size = count($input);
$graph = [];
$start = [0, 0];
$finish = [$size - 1, $size - 1];
echo "preparing grid (hold on to your memory!) \n";
// Create nodes
foreach ($input as $y => $row) {
    $graph[$y] = [];
    foreach (str_split($row) as $x => $costs) {
        $graph[$y][$x] = new Node([$x, $y], (int) $costs);
    }
}

// Set neighbours
foreach ($graph as $y => $row) {
    if ($y == 0) {
        $neighbourYs = [$y + 1];
    } elseif ($y == $size - 1) {
        $neighbourYs = [$y - 1];
    } else {
        $neighbourYs = [$y + 1, $y - 1];
    }

    foreach ($row as $x => $node) {
        if ($x == 0) {
            $neighbourXs = [$x + 1];
        } elseif ($x == $size - 1) {
            $neighbourXs = [$x - 1];
        } else {
            $neighbourXs = [$x + 1, $x - 1];
        }
        $neighbours = [];
        foreach ($neighbourYs as $neighbourY) {
            $neighbours[] = $graph[$neighbourY][$x];
        }
        foreach ($neighbourXs as $neighbourX) {
            $neighbours[] = $graph[$y][$neighbourX];
        }

        $graph[$y][$x]->setNeighbours($neighbours);
    }
}

$costFunction = function (array $curCoord, $finishCoord) {
    return (abs($curCoord[0] - $finishCoord[0]) + abs($curCoord[1] - $finishCoord[1])) * 1;
};

echo "Finding cheapest path value: \n";

var_dump(aStar($start, $finish, $costFunction, $graph));

function aStar(array $startCoords, $goalCoords, $costFunction, $graph): int
{
    /** @var Node $startNode */
    $startNode = $graph[$startCoords[0]][$startCoords[1]];
    $startNode->setPathCosts(0);
    $myQueue = [$startNode];

    while (count($myQueue) > 0) {
        /** @var Node $curNode */
        $curNode = array_shift($myQueue);

        if ($curNode->getCoords() == $goalCoords) {
            // Yay!
            return $curNode->getPathCosts();
        }

        /** @var Node $neighbour */
        foreach ($curNode->getNeighbours() as $neighbour) {
            $pathScore = $curNode->getPathCosts() + $neighbour->getNodeCosts();
            if ($pathScore < $neighbour->getPathCosts()) {
                $neighbour->setPathCosts($pathScore);
                $coords = $neighbour->getCoords();
                $neighbour->setFullPathCosts($priority = $pathScore + $costFunction($coords, $goalCoords));

                $myQueue[sprintf('%s|%s', $coords[0], $coords[1])] = $neighbour;
            }
        }

        uasort($myQueue, function(Node $a, Node $b) {return $a->getFullPathCosts() <=> $b->getFullPathCosts();});
    }

    return PHP_INT_MAX;
}

class Node
{
    private int $nodeCosts;
    private array $coords;
    private int $pathCosts = PHP_INT_MAX;
    private int $fullPathCosts = PHP_INT_MAX;
    private ?Node $parent = null;
    private array $neighbours = [];

    public function __construct(array $coords, int $nodeCosts)
    {
        $this->nodeCosts = $nodeCosts;
        $this->coords = $coords;
    }

    /**
     * @return int
     */
    public function getNodeCosts(): int
    {
        return $this->nodeCosts;
    }

    /**
     * @param int $nodeCosts
     */
    public function setNodeCosts(int $nodeCosts): void
    {
        $this->nodeCosts = $nodeCosts;
    }

    /**
     * @return array
     */
    public function getCoords(): array
    {
        return $this->coords;
    }

    /**
     * @param array $coords
     */
    public function setCoords(array $coords): void
    {
        $this->coords = $coords;
    }

    /**
     * @return int
     */
    public function getPathCosts(): int
    {
        return $this->pathCosts;
    }

    /**
     * @param int $pathCosts
     */
    public function setPathCosts(int $pathCosts): void
    {
        $this->pathCosts = $pathCosts;
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

    /**
     * @return array
     */
    public function getNeighbours(): array
    {
        return $this->neighbours;
    }

    /**
     * @param array $neighbours
     */
    public function setNeighbours(array $neighbours): void
    {
        $this->neighbours = $neighbours;
    }

    /**
     * @return int
     */
    public function getFullPathCosts(): int
    {
        return $this->fullPathCosts;
    }

    /**
     * @param int $fullPathCosts
     */
    public function setFullPathCosts(int $fullPathCosts): void
    {
        $this->fullPathCosts = $fullPathCosts;
    }
}
