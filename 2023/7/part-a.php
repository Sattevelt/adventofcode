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
    $hands = [];
    foreach ($data as $line) {
        $parts = explode(' ', $line);
        $hands[] = new Hand($parts[0], (int)$parts[1]);
    }

    usort(
        $hands,
        function (Hand $a, Hand $b) {
            return $a->getScore() <=> $b->getScore();
        }
    );

    $totalScore = 0;
    foreach ($hands as $key => $hand) {
        $multiplier = $key + 1;
        $bet = $hand->getBet();
        $winning = $bet * $multiplier;
        $totalScore += $winning;
        echo sprintf('%s: %s x %s => %s' . PHP_EOL, $hand->getHand(), $multiplier, $bet, $winning);
    }

    return $totalScore;
}

class Hand
{
    private static array $cardValues = [
        '1' => '01',
        '2' => '02',
        '3' => '03',
        '4' => '04',
        '5' => '05',
        '6' => '06',
        '7' => '07',
        '8' => '08',
        '9' => '09',
        'T' => '10',
        'J' => '11',
        'Q' => '12',
        'K' => '13',
        'A' => '14',
    ];

    private int $score = 0;
    private int $bet = 0;

    private string $hand = '';

    public function __construct(string $hand, int $bet)
    {
        $cardsScore = '';
        $sortedCards = [];
        $cards = str_split($hand);
        foreach ($cards as $card) {
            $cardsScore .= self::$cardValues[$card];
            if (!isset($sortedCards[$card])) {
                $sortedCards[$card] = 0;
            }
            $sortedCards[$card]++;
        }
        rsort($sortedCards, SORT_NUMERIC);
        $handScore = match ($sortedCards[0]) {
            5 => '9',
            4 => '8',
            3 => $sortedCards[1] === 2 ? '7' : '6',
            2 => $sortedCards[1] === 2 ? '5' : '4',
            default => '3',
        };

        $this->hand = $hand;
        $this->bet = $bet;
        $this->score = (int)($handScore . $cardsScore);
    }

    /**
     * @return int
     */
    public function getScore(): int
    {
        return $this->score;
    }

    /**
     * @return int
     */
    public function getBet(): int
    {
        return $this->bet;
    }

    /**
     * @return string
     */
    public function getHand(): string
    {
        return $this->hand;
    }

    public function __toString(): string
    {
        return (string)$this->score;
    }
}