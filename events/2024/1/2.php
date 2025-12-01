<?php

$input = AOC\Lib\Input::inputAsArray($input);

$list1 = [];
$list2 = [];
foreach ($input as $value) {
    if ($value === '') {
        continue;
    }
    $values = explode("   ", $value);
    $list1[] = (int)$values[0];
    $rightValue = (int)$values[1];
    if (array_key_exists($rightValue, $list2)) {
        $list2[$rightValue]++;
    } else {
        $list2[$rightValue] = 1;
    }
}
sort($list1);

$score = 0;
for ($i = 0; $i < count($list1); $i++) {
    $val1 = $list1[$i];
    $val2 = $list2[$val1] ?? 0;
    $score += $val1 * $val2;
}

return $score;
