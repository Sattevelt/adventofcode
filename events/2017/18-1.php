<?php

$input = explode("\n", 'set i 31
set a 1
mul p 17
jgz p p
mul a 2
add i -1
jgz i -2
add a -1
set i 127
set p 680
mul p 8505
mod p a
mul p 129749
add p 12345
mod p a
set b p
mod b 10000
snd b
add i -1
jgz i -9
jgz a 3
rcv b
jgz b -1
set f 0
set i 126
rcv a
rcv b
set p a
mul p -1
add p b
jgz p 4
snd a
set a b
jgz 1 3
snd b
set f 1
add i -1
jgz i -11
snd a
jgz f -16
jgz a -19');
$test = explode("\n", 'set a 1
add a 2
mul a a
mod a 5
snd a
set a 0
rcv a
jgz a -1
set a 1
jgz a -2');
//$input = $test;

$register = [];
$playedFreq = INF;

$regex = '/^([a-z]{3}) (\-?[a-z0-9]+)( (\-?[a-z0-9]+))?$/';

for ($i = 0; $i < count($input); $i++) {
    $matches = [];
    preg_match($regex, $input[$i],$matches);
    $action = $matches[1];

    $matchX = $matches[2];
    $valueX = 0;
    if (is_numeric($matchX)) {
        $valueX = (int) $matchX;
    } elseif (! isset($register[$matchX])) {
        $register[$matchX] = 0;
    } else {
        $valueX = $register[$matchX];
    }

    $matchY = isset($matches[4]) ? $matches[4] : null;
    $valueY = 0;
    if (is_numeric($matchY)) {
        $valueY = (int) $matchY;
    } elseif (! isset($register[$matchY])) {
        $register[$matchY] = 0;
    } else {
        $valueY = $register[$matchY];
    }
//    echo "\n*****************************\n"
//        . $input[$i] ."\n";

    switch ($action) {
        case 'snd':
            echo "Play freq: $valueX\n";
            $playedFreq = $valueX;
            break;
        case 'set':
            $register[$matchX] = $valueY;
            break;
        case 'add':
            $register[$matchX] = $valueX + $valueY;
            break;
        case 'mul':
            $register[$matchX] = $valueX * $valueY;
            break;
        case 'mod':
            $register[$matchX] = $valueX % $valueY;
            break;
        case 'rcv':
            if ($valueX != 0) {
                echo "Recover freq: $playedFreq\n";
                die;
            }
            break;
        case 'jgz':
            if ($valueX > 0) {
                $i += $valueY - 1;
            }
            break;
    }
//    print_r($register);

}



