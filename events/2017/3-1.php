<?php

$input = 347991;

echo createGrid($input) . "\n\n";


function createGrid($target)
{
    $dirs = [
        'l' => new Coord(-1, 0),
        'r' => new Coord(1, 0),
        'u' => new Coord(0, 1),
        'd' => new Coord(0, -1)
    ];
    $dirOrder = ['r', 'u', 'l', 'd'];
    $dirIndex = 0;
    $dirChanges = 0;
    $dirLength = 1;
    $curLength = 0;

    $pos = new Coord(0, 0);
    $curNum = 1;

    while ($curNum < $target) {
        if ($curLength >= $dirLength) { // Direction change
            $dirIndex = ($dirIndex + 1) % 4; // Get next direction index
            $curLength = 0; // reset current path length
            $dirChanges++; // Add to number of direction changes
            if ($dirChanges % 2 === 0) { // After 2 direction changes, increment pathlength
                $dirLength++;
            }
        }
        $pos = $pos->add($dirs[$dirOrder[$dirIndex]]);
        $curLength++;
        $curNum++;
    }

    return abs($pos->x) + abs($pos->y);
}

class Coord
{
    public $x;
    public $y;

    public function __construct($x, $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function add(Coord $vector)
    {
        return new Coord($vector->x + $this->x, $vector->y + $this->y);
    }
}
