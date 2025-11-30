<?php

declare(strict_types=1);

require_once('common.php');
$input = getPuzzleInput(12);
$input = explode("\r\n", $input);
//$input = explode("\n", "fs-end
//he-DX
//fs-he
//start-DX
//pj-DX
//end-zg
//zg-sl
//zg-pj
//pj-he
//RW-he
//fs-DX
//pj-RW
//zg-RW
//start-pj
//he-WI
//zg-he
//pj-fs
//start-RW");

$regex = '/^([^\-]+)\-([^\-]+)$/';
$map = [];
$start = null;
foreach ($input as $line) {
    $matches = [];
    preg_match($regex, $line, $matches);
    $name1 = $matches[1];
    $name2 = $matches[2];

    if (! array_key_exists($name1, $map)) {
        $node1 = new Node($name1);
        $map[$name1] = $node1;
        if ($name1 === 'start') {
            $start = $node1;
        }
    } else {
        $node1 = $map[$name1];
    }

    if (! array_key_exists($name2, $map)) {
        $node2 = new Node($name2);
        $map[$name2] = $node2;
    } else {
        $node2 = $map[$name2];
        if ($name2 === 'start') {
            $start = $node2;
        }
    }

    $node1->addConnection($node2);
    $node2->addConnection($node1);
}


$paths = getPathsToEnd($start, new Path());
//foreach ($paths as $index => $path) {
//    echo sprintf("%s: %s\n", $index, $path->toString());
//}
var_dump(count($paths));


function getPathsToEnd(Node $curNode, Path $path): array
{
    $paths = [];
    $path->addNode($curNode);
    $connections = $curNode->getConnections();
    if (count($connections) === 0) {
        $paths[] = $path;
    } else {
        foreach ($connections as $connection) {
            $curPath = clone $path;
            if ($connection->getName() === 'end') {
                $curPath->addNode($connection);
                $paths[] = $curPath;
//                die;
                continue; // End found
            } elseif (! $curPath->mayVisitNode($connection)) {
                continue; //small cave already visited
            }
            $foundPaths = getPathsToEnd($connection, $curPath);
            foreach ($foundPaths as $foundPath) {
                $paths[] = $foundPath;
            }
        }
    }
    return $paths;
}

class Path
{
    private array $nodes = [];
    private bool $smallCaveVistedTwice = false;

    public function addNode(Node $node)
    {
//        echo sprintf("%s (%s)\n", $node->getName(), $this->smallCaveVistedTwice ? 'true' : 'false');
        if ($this->hasNode($node) > 0 && ! $node->isMultipleAllowed()) {
            $this->smallCaveVistedTwice = true;
        }
        $this->nodes[] = $node;
    }

    public function hasNode(Node $node) {
        $times = 0;
        foreach ($this->nodes as $curNode) {
            if ($node->getName() === $curNode->getName()) {
                $times++;
            }
        }
        return $times;
    }

    public function mayVisitNode(Node $node): bool
    {
        $times = $this->hasNode($node);
        if ($times > 0) {
            if ($node->getName() === 'start' || $node->getName() === 'end') {
                return false;
            }
        }
        if ($times === 1 && ! $node->isMultipleAllowed() && $this->smallCaveVistedTwice) {
            return false;
        }
        if ($times > 1 && ! $node->isMultipleAllowed()) {
            return false;
        }

        return true;
    }

    public function toString(): string
    {
        $nodeNames = [];
        foreach ($this->nodes as $node) {
            $nodeNames[] = $node->getName();
        }
        return implode(',', $nodeNames);
    }
}

class Node
{
    private string $name;
    private bool $multipleVisits = false;
    private array $connections = [];

    public function __construct(string $name)
    {
        $this->name = $name;
        $ascii = ord($name);
        if ($ascii >= 65 && $ascii <= 90) {
            $this->multipleVisits = true;
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isMultipleAllowed(): bool
    {
        return $this->multipleVisits;
    }

    public function getConnections(): array
    {
        return $this->connections;
    }

    public function hasConnection(Node $node): bool
    {
        foreach ($this->connections as $connection) {
            if ($connection->getName() === $node->getName()) {
                return true;
            }
        }
        return false;
    }

    public function addConnection(Node $node)
    {
        if (!$this->hasConnection($node)) {
            $this->connections[] = $node;
        }
    }
}


