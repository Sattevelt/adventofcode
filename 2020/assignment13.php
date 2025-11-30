<?php

$data = [17,'x','x','x','x','x','x','x','x','x','x',37,'x','x','x','x','x',571,'x','x','x','x','x','x','x','x','x','x','x','x','x','x','x','x','x',13,'x','x','x','x',23,'x','x','x','x','x',29,'x',401,'x','x','x','x','x','x','x','x','x',41,'x','x','x','x','x','x','x','x',19];


$step = 1;
$common = 0;

foreach ($data as $index => $busId) {
    if ($busId == 'x') {
        continue;
    }
    while (($common + $index) % $busId != 0) {
        $common += $step;
    }
    $step *= $busId;
}

echo $common . "\n";
