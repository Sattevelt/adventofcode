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
$counts = [];
$pairs = [];
// Extract rules and initials counts
foreach ($input as $line) {
    $matches = [];
    preg_match($regex, $line, $matches);
    $rules[$matches[1]] = $matches[2];
    if (! array_key_exists($matches[2], $counts)) {
        $counts[$matches[2]] = 0;
    }
}
// Add counts from template
foreach (str_split($template) as $char) {
    $counts[$char]++;
}

// Fill first pairs
for ($i = 0; $i < strlen($template) - 1; $i++) {
    $pair = substr($template, $i, 2);
    if (! isset($pairs[$pair])) {
        $pairs[$pair] = 0;
    }
    $pairs[$pair]++;
}

$iterations = 40;
for ($i = 0; $i < $iterations; $i++) {
    foreach ($pairs as $pair => $count) {
        $newChar = $rules[$pair];
        $counts[$newChar] += $count;

        $pairs[$pair] -= $count;
        $newPair1 = substr($pair, 0, 1) . $newChar;
        if (! isset($pairs[$newPair1])) {
            $pairs[$newPair1] = 0;
        }
        $pairs[$newPair1] += $count;

        $newPair2 = $newChar . substr($pair, 1, 1);
        if (! isset($pairs[$newPair2])) {
            $pairs[$newPair2] = 0;
        }
        $pairs[$newPair2] += $count;
    }
}

asort($counts);
print_r($pairs);print_r($counts);

echo end($counts) - reset($counts) . "\n";
