<?php

$data = [
    'Player 1:',
    '14',
    '23',
    '6',
    '16',
    '46',
    '24',
    '13',
    '25',
    '17',
    '4',
    '31',
    '7',
    '1',
    '47',
    '15',
    '9',
    '50',
    '3',
    '30',
    '37',
    '43',
    '10',
    '28',
    '33',
    '32',
    '',
    'Player 2:',
    '29',
    '49',
    '11',
    '42',
    '35',
    '18',
    '39',
    '40',
    '36',
    '19',
    '48',
    '22',
    '2',
    '20',
    '26',
    '8',
    '12',
    '44',
    '45',
    '21',
    '38',
    '41',
    '34',
    '5',
    '27'
];

//$data = [
//    'Player 1:',
//    '9',
//    '2',
//    '6',
//    '3',
//    '1',
//    '',
//    'Player 2:',
//    '5',
//    '8',
//    '4',
//    '7',
//    '10'
//];

$curIndex = 1;
$playerCards = [1 => [], 2 => []];
foreach ($data as $line) {
    if ($line == '') {
        $curIndex++;
        continue;
    }
    if (is_numeric($line)) {
        $playerCards[$curIndex][] = (int)$line;
    }
}

$winner = playGame($playerCards);
//echo "\nOverall winner is player $winner!\n";

$cardCount = count($playerCards[$winner]);
$score = 0;
foreach($playerCards[$winner] as $card) {
    $score += $cardCount * $card;
    $cardCount--;
}
echo "Winner of game is player $winner!\n";
echo "Final score is: $score\n";

function playGame(array &$playerCards) {
//    echo "=== NEW GAME ===\n";
    $decksPlayed = [];
    while (count($playerCards[1]) > 0 && count($playerCards[2]) > 0) {
//        echo ".";
        if (in_array($playerCards, $decksPlayed, true)) {
//            print_r($playerCards);
//            echo "#\n";
            return 1;
        }
        $copyDeck = $playerCards;
        $decksPlayed[] = $copyDeck;
//        printf(
//            "\nNew round\nPlayer1 deck: %s\nPlayer2 deck: %s\nPlayer1 card: %s\nPlayer2 card: %s\n",
//            implode(',', $playerCards[1]),
//            implode(',', $playerCards[2]),
//            reset($playerCards[1]),
//            reset($playerCards[2])
//        );
        $player1Card = array_shift($playerCards[1]);
        $player2Card = array_shift($playerCards[2]);

        if (count($playerCards[1]) >= $player1Card && count($playerCards[2]) >= $player2Card) {
//            echo "Playing a sub-game to determine the winner...\n\n";
            $tmpPlayerCards = [
                1 => array_slice($playerCards[1], 0, $player1Card),
                2 => array_slice($playerCards[2], 0, $player2Card)
            ];
            $winner = playGame($tmpPlayerCards);
        } else {
            $winner = ($player1Card > $player2Card ? 1 : 2);
        }
        $playerCards[$winner][] = ($winner === 1 ? $player1Card : $player2Card);
        $playerCards[$winner][] = ($winner === 1 ? $player2Card : $player1Card);
//        echo "Winner of round is player $winner";

    }
    $winner = (count($playerCards[1]) > 0 ? 1 : 2);
//    echo "Winner of game is player $winner!\n";
    return $winner;
}
