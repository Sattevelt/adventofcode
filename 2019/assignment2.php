<?php

$data = explode(',', '1,0,0,3,1,1,2,3,1,3,4,3,1,5,0,3,2,1,10,19,2,9,19,23,1,9,23,27,2,27,9,31,1,31,5,35,2,35,9,39,1,39,10,43,2,43,13,47,1,47,6,51,2,51,10,55,1,9,55,59,2,6,59,63,1,63,6,67,1,67,10,71,1,71,10,75,2,9,75,79,1,5,79,83,2,9,83,87,1,87,9,91,2,91,13,95,1,95,9,99,1,99,6,103,2,103,6,107,1,107,5,111,1,13,111,115,2,115,6,119,1,119,5,123,1,2,123,127,1,6,127,0,99,2,14,0,0');



for ($i = 0; $i < 100; $i++) {
    for ($j = 0; $j < 100; $j++) {
        $tryData = $data;
        $tryData[1] = $i;
        $tryData[2] = $j;

        $tryData = calc($tryData);
        if ($tryData[0] === 19690720) {
            echo "noun: $i || verb: $j || " . (($i * 100) + $j) . "\n";
        }
    }
}

function calc($data) {
    $pointer = 0;
    while ($data[$pointer] != 99) {
        if(! in_array($data[$pointer], [1,2])) {
            die("invalid value at index $pointer: " . $data[$pointer] . "\n");
        }
        $num1 = $data[$data[$pointer + 1]];
        $num2 = $data[$data[$pointer + 2]];
        $num3 = $data[$pointer + 3];
        if ($data[$pointer] == 1) {
            $data[$num3] = $num1 + $num2;
        } elseif($data[$pointer] == 2) {
            $data[$num3] = $num1 * $num2;
        }
        $pointer += 4;
    }
    return $data;
}