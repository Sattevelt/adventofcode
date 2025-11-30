<?php

$data = [
    'deal into new stack',
    'deal with increment 57',
    'cut -4643',
    'deal with increment 59',
    'cut 5189',
    'deal into new stack',
    'deal with increment 24',
    'cut 3207',
    'deal with increment 63',
    'cut 3839',
    'deal with increment 53',
    'cut -1014',
    'deal with increment 21',
    'cut -3150',
    'deal into new stack',
    'deal with increment 39',
    'cut 900',
    'deal with increment 6',
    'deal into new stack',
    'deal with increment 65',
    'cut 6108',
    'deal with increment 54',
    'cut 6343',
    'deal with increment 26',
    'deal into new stack',
    'cut 8625',
    'deal with increment 8',
    'cut -1956',
    'deal into new stack',
    'cut 8750',
    'deal with increment 43',
    'cut -2930',
    'deal with increment 10',
    'cut -2359',
    'deal with increment 34',
    'cut 390',
    'deal with increment 46',
    'cut 5467',
    'deal into new stack',
    'cut 61',
    'deal with increment 4',
    'cut -332',
    'deal into new stack',
    'deal with increment 74',
    'cut -2568',
    'deal with increment 54',
    'deal into new stack',
    'deal with increment 47',
    'cut -9034',
    'deal with increment 74',
    'cut 2174',
    'deal into new stack',
    'deal with increment 63',
    'cut -3966',
    'deal with increment 16',
    'cut 1619',
    'deal with increment 43',
    'deal into new stack',
    'cut 2779',
    'deal into new stack',
    'cut -1441',
    'deal with increment 52',
    'cut 362',
    'deal with increment 25',
    'cut -5105',
    'deal into new stack',
    'deal with increment 25',
    'cut 5744',
    'deal with increment 69',
    'deal into new stack',
    'cut 6645',
    'deal with increment 49',
    'cut -9379',
    'deal with increment 2',
    'cut 2768',
    'deal with increment 21',
    'cut 6900',
    'deal with increment 67',
    'cut -4226',
    'deal with increment 12',
    'cut 2541',
    'deal with increment 70',
    'cut -9160',
    'deal with increment 19',
    'deal into new stack',
    'cut -7165',
    'deal with increment 74',
    'deal into new stack',
    'deal with increment 65',
    'cut 298',
    'deal with increment 24',
    'deal into new stack',
    'deal with increment 29',
    'cut 7412',
    'deal with increment 30',
    'cut -3224',
    'deal into new stack',
    'cut -7213',
    'deal with increment 45',
    'cut 8295'
];

//$data = [
//    'deal with increment 7',
//    'deal into new stack',
//    'deal into new stack'
//];
//
//$data = [
//    'cut 6',
//    'deal with increment 7',
//    'deal into new stack'
//];
//
//$data = [
//    'deal with increment 7',
//    'deal with increment 9',
//    'cut -2'
//];
//
//$data = [
//    'deal into new stack',
//    'cut -2',
//    'deal with increment 7',
//    'cut 8',
//    'cut -4',
//    'deal with increment 7',
//    'cut 3',
//    'deal with increment 9',
//    'deal with increment 3',
//    'cut -1'
//];
//$data = [
//    'deal with increment 2',
//];

$data = array_reverse($data); // Going backward for this one.
$deckSize = 10007;

$curIndex = 2020;

foreach ($data as $instruction) {
    $matches = [];
    if ($instruction === 'deal into new stack') {
        $curIndex = $deckSize - $curIndex - 1;
    } elseif (preg_match('/^cut (-?[0-9]+)$/', $instruction, $matches) === 1) {
        $cutSize = (int)$matches[1];
        if ($cutSize > 0) {
            if ($cutSize - 1 >= $curIndex) {
                // curIndex is inside the cut.
                $curIndex = ($deckSize - $cutSize) + $curIndex;
            } else {
                $curIndex -= $cutSize;
            }
        } elseif ($cutSize < 0) {
            $remainingDeckSize = $deckSize - abs($cutSize);
            if ($remainingDeckSize <= $curIndex) {
                // curIndex in inside the cut
                $curIndex -= $remainingDeckSize;
            } else {
                $curIndex += abs($cutSize);
            }
        }
    } elseif (preg_match('/^deal with increment ([0-9]+)$/', $instruction, $matches) === 1) {
        $increment = (int)$matches[1];
        $curIndex = ($curIndex * $increment) % $deckSize;
    }
    echo ": $curIndex\n";
}

//Card in position 2020: 9553
echo $curIndex . "\n";
//echo "Card in position 2020: " . $curIndex . "\n";