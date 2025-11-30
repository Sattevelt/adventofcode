<?php

declare(strict_types=1);

require_once('common.php');
$input = getPuzzleInput(14);
$input = explode("\r\n", $input);
//$input = explode("\n", "NNCB
//
//CH -> B
//HH -> N
//CB -> H
//NH -> C
//HB -> C
//HC -> B
//HN -> C
//NN -> C
//BH -> H
//NC -> B
//NB -> B
//BN -> B
//BB -> N
//BC -> B
//CC -> N
//CN -> C");

$template = $input[0];
array_shift($input);array_shift($input);

$regex = '/^([A-Z]{2}) \-\> ([A-Z])$/';
$rules = [];
foreach ($input as $line) {
    $matches = [];
    preg_match($regex, $line, $matches);
    $rules[$matches[1]] = $matches[2];
}

$iterations = 10;
for ($i = 0; $i < $iterations; $i++) {
    $len = strlen($template);
    $newString = '';
    for ($x = 0; $x < $len; $x++) {
        $newString .= substr($template, $x, 1);
        $key = substr($template, $x, 2);
        if (isset($rules[$key])) {
            $newString .= $rules[$key];
        }
    }
    $template = $newString;
}

$scores = [];
foreach (str_split($template) as $char) {
    if (! array_key_exists($char, $scores)) {
        $scores[$char] = 0;
    }
    $scores[$char]++;
}
asort($scores);

echo end($scores) - reset($scores) . "\n";


