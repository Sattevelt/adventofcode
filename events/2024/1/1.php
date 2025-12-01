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
    $list2[] = (int)$values[1];
}
sort($list1);
sort($list2);

$diff = 0;
for ($i = 0; $i < count($list1); $i++) {
    $diff += abs($list1[$i] - $list2[$i]);
}

return $diff;
