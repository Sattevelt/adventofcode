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
    $valves = [];
    $regex = '/^Valve ([A-Z]{2}) has flow rate=([0-9]+); tunnels? leads? to valves? ([A-Z]{2}(, [A-Z]{2})*)/';
    foreach ($data as $line) {
        preg_match($regex, $line, $mat);
        $tunnels = [];
        foreach (explode(', ', $mat[3]) as $label) {
            $tunnels[$label] = 1;
        }
        $valves[$mat[1]] = new Valve($mat[1], (int)$mat[2], $tunnels);
    }

    buildGraph($valves, $valves['AA']);

    $maxScore = solve($valves, $valves['AA'], 30, 0);

    return $maxScore;
}

run(false);

function solve(array $valves, Valve $start, int $minute, int $score, array $open = []): int
{
    if ($minute <= 0) {
        return $score;
    }
    $flowRate = $start->getFlowRate();
    $startLabel = $start->getLabel();
    if (! in_array($startLabel, $open) && $start->getFlowRate() > 0) {
        $minute--;
        $score += $flowRate * ($minute);
        $open[] = $startLabel;
    }

    $scores = [$score];
    foreach ($start->getTunnels() as $label => $cost) {
        if (!in_array($label, $open) && isset($valves[$label])) {
            $scores[] = solve($valves, $valves[$label], $minute - $cost, $score, $open);
        }
    }

    rsort($scores);
    return $scores[0];
}

function buildGraph(array $valves, Valve $start)
{
    /** @var Valve $valve */
    foreach ($valves as $valve) {
        $valve->setTunnels(getNonZeroTargets($valve, $valves));
    }

    // Remove zero flow tunnels, except for AA (start)
    foreach ($valves as $label => $valve) {
        if ($valve->getFlowRate() === 0 && $valve->getLabel() !== 'AA') {
            unset($valves[$label]);
        }
    }
    optimiseGraph($valves);
}

function optimiseGraph(array $valves): void
{
    foreach ($valves as $label => $valve) {
        $target = $label === 'AA' ? count($valves) - 1 : count($valves) - 2;
        $tunnels = $valve->getTunnels();

        while(count($tunnels) < $target) {
            foreach ($tunnels as $step1Label => $step1Cost) {
                if ($step1Label === $label) {
                    continue; // Do go to yourself;
                }
                $step2Tunnels = $valves[$step1Label]->getTunnels();
                foreach ($step2Tunnels as $step2Label => $step2Cost) {
                    if ($step2Label === $label || $step2Label === $step1Label) {
                        continue;
                    }
                    $newCost = $step1Cost + $step2Cost;
                    if (isset($tunnels[$step2Label]) && $tunnels[$step2Label] <= $newCost) {
                        continue;
                    }
                    $tunnels[$step2Label] = $newCost;
                }
            }
        }
        $valve->setTunnels($tunnels);
    }

    reportTunnels($valves);
}

function reportTunnels(array $valves): void
{
    foreach ($valves as $valve) {
        echo $valve->getLabel() . ': ';
        $tunnelStr = [];
        foreach ($valve->getTunnels() as $label => $cost) {
            $tunnelStr[] = sprintf('%s(%s)', $label, $cost);
        }
        echo implode(', ', $tunnelStr) . "\n";
    }
}

function getNonZeroTargets(Valve $start, array $valves, $ignore = []): array
{
    $targets = [];
    $ignore[] = $start->getLabel();
    foreach ($start->getTunnels() as $label => $cost) {
        if (in_array($label, $ignore)) {
            continue;
        }
        $ignore[] = $label;

        if ($valves[$label]->getFlowRate() === 0) {
            $subTargets = getNonZeroTargets($valves[$label], $valves, $ignore);
            foreach ($subTargets as $subLabel => $subCost) {
                if (isset($targets[$subLabel]) && $targets[$subLabel] <= $subCost) {
                    continue;
                }
                if ($subLabel === $start->getLabel()) {
                    continue;
                }
                $targets[$subLabel] = $subCost + 1;
            }
        } else {
            if (isset($targets[$label]) && $targets[$label] <= $cost) {
                continue;
            }
            $targets[$label] = $cost;
        }
    }

    return $targets;
}

class Valve
{
    private string $label;
    private int $flowRate;
    private array $tunnels;

    private bool $open = false;

    public function __construct(string $label, int $flowRate, array $tunnels)
    {
        $this->label = $label;
        $this->flowRate = $flowRate;
        $this->tunnels = $tunnels;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return int
     */
    public function getFlowRate(): int
    {
        return $this->flowRate;
    }

    /**
     * @return array
     */
    public function getTunnels(): array
    {
        return $this->tunnels;
    }

    /**
     * @param array $tunnels
     */
    public function setTunnels(array $tunnels): void
    {
        $this->tunnels = $tunnels;
    }

    public function setTunnel(string $label, int $cost): void
    {
        $this->tunnels[$label] = $cost;
    }

    /**
     * @return bool
     */
    public function isOpen(): bool
    {
        return $this->open;
    }

    /**
     * @param bool $open
     */
    public function open(): void
    {
        $this->open = true;
    }
}
