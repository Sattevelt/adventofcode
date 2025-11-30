<?php

$data = [9,6,0,10,18,2,1];

//$data = [3,1,2];


$numberTurns = [];
$numberSpoken = null;
$start = hrtime(true);
echo "1";
for ($turn = 1; $turn <= 30000000; $turn++) {
//    echo "Turn $turn: ";
    if (count($data)) {
        $nextNumberSpoken = array_shift($data);
//        echo "Number from list: $nextNumberSpoken\n";
    } elseif (array_key_exists($numberSpoken, $numberTurns)) {
        $nextNumberSpoken = $turn - $numberTurns[$numberSpoken];
//        echo "$numberSpoken was said before at turn " . ($numberTurns[$numberSpoken]) . ", turns ago: ". $nextNumberSpoken . "\n";
    } else {
//        echo "$numberSpoken was not heard before: 0\n";
        $nextNumberSpoken = 0;
    }

    $numberTurns[$numberSpoken] = $turn;
    $numberSpoken = $nextNumberSpoken;
    if ($turn % 1000 === 0) {
        echo "\r$turn";
    }
}
print_r($numberTurns);
$duration = round((hrtime(true) - $start) /  (1e+9), 2);
$speed = round(30000000 / $duration, 0);
echo sprintf("\ntook %s seconds, or %s iterations per second.\n", $duration, $speed);
echo "\n$numberSpoken\n";
//print_r($numberTurns);
