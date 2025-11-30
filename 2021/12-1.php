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

$paths = getPathsToEnd($start, []);
foreach ($paths as $index => $path) {
    echo sprintf("%s: %s\n", $index, implode(',', $path));
}
var_dump(count($paths));


function getPathsToEnd(Node $curNode, array $path): array
{
    $paths = [];
    $path[] = $curNode->getName();
    $connections = $curNode->getConnections();
    if (count($connections) === 0) {
        $paths[] = $path;
    } else {
        foreach ($curNode->getConnections() as $connection) {
            $curPath = $path;
            if ($connection->getName() === 'end') {
                $curPath[] = $connection->getName();
                $paths[] = $curPath;
                continue; // End found
            } elseif (!$connection->isMultipleAllowed() && in_array($connection->getName(), $curPath, true)) {
                continue; //small cave already visited
            }
            $foundPaths = getPathsToEnd($connection, $path);
            foreach ($foundPaths as $foundPath) {
                $paths[] = $foundPath;
            }
        }
    }
    return $paths;
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


