<?php

return [
    [
        'name' => 'pre-test1',
        'input' => 'twone' . PHP_EOL,
        'solution' => 21
    ],
    [
        'name' => 'pre-test2',
        'input' => 'oneight' . PHP_EOL,
        'solution' => 18
    ],
    [
        'name' => 'test 1',
        'input' => <<<END
two1nine
eightwothree
abcone2threexyz
xtwone3four
4nineeightseven2
zoneight234
7pqrstsixteen
END
,
        'solution' => 281,
    ],
];
