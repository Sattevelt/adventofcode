<?php

declare(strict_types=1);

require_once('common.php');
$input = getPuzzleInput(18);
$input = explode("\r\n", $input);
//$input = explode("\n", "[[[0,[5,8]],[[1,7],[9,6]]],[[4,[1,2]],[[1,4],2]]]
//[[[5,[2,8]],4],[5,[[9,9],0]]]
//[6,[[[6,2],[5,6]],[[7,6],[4,7]]]]
//[[[6,[0,7]],[0,9]],[4,[9,[9,0]]]]
//[[[7,[6,4]],[3,[1,3]]],[[[5,5],1],9]]
//[[6,[[7,3],[3,2]]],[[[3,8],[5,7]],4]]
//[[[[5,4],[7,7]],8],[[8,3],8]]
//[[9,3],[[9,9],[6,[4,9]]]]
//[[2,[[7,7],7]],[[5,8],[[9,3],[0,2]]]]
//[[[[5,2],5],[8,[3,7]]],[[5,[7,5]],[4,4]]]");

$maxMag = 0;
for ($i = 0; $i < count($input); $i++) {
    for ($j = 0; $j < count($input); $j++) {
        if ($i === $j) {
            continue;
        }
        $newMag = getMagnitude(
            reduce(
                add(reduce($input[$i]), reduce($input[$j]))
            )
        );
        $maxMag = max(
            $maxMag,
            $newMag
        );
    }
}
echo $maxMag . PHP_EOL;die;

function reduce(string $number): string
{
    $pairRegex = '/^\[([0-9]+),([0-9]+)\]/';
    $numRegex = '/^([0-9]+)/';
    while (true) {
//        echo "    " . $number . "\n";
        // Check for deeply nested pairs
        $depth = 0;
        for ($pos = 0; $pos < strlen($number); $pos++) {
            $char = $number[$pos];
            switch ($char) {
                case '[':
                    $depth++;
                    $matches = [];
                    if ($depth > 4 && preg_match($pairRegex, substr($number, $pos), $matches) === 1) {
                        $pairLength = strlen($matches[0]);
                        // EXPLODE!!
                        // Right number to the right
                        for ($subPos = $pos + $pairLength + 1; $subPos < strlen($number); $subPos++) {
                            if (is_numeric($number[$subPos])) {
                                $subLength = is_numeric($number[$subPos + 1]) ? 2 : 1;
                                $replacement = (int)substr($number, $subPos, $subLength) + (int)$matches[2];
                                $number = substr_replace($number, (string)$replacement, $subPos, $subLength);
                                break;
                            }
                        }

                        // Replace pair with 0
                        $number = substr_replace($number, '0', $pos, $pairLength);

                        // Left number to the left.
                        for ($subPos = $pos - 2; $subPos >= 0; $subPos--) {
                            if (is_numeric($number[$subPos])) {
                                $subLength = 1;
                                if (is_numeric($number[$subPos - 1])) {
                                    $subLength = 2;
                                    $subPos--;
                                }
                                $replacement = (int)substr($number, $subPos, $subLength) + (int)$matches[1];
                                $number = substr_replace($number, (string)$replacement, $subPos, $subLength);
                                break;
                            }
                        }
                        continue 3; // next iteration of outer while loop
                    }
                    break;
                case ']':
                    $depth--;
                    break;
            }
        }

        // Check for numbers > 9
        for ($pos = 0; $pos < strlen($number); $pos++) {
            $char = $number[$pos];
            if (is_numeric($char)) {
                $matches = [];
                preg_match($numRegex, substr($number, $pos, 2), $matches);
                if ((int)$matches[0] > 9) {
                    // SPLIT
                    $matchLen = strlen($matches[0]);
                    $match = (int)$matches[0];
                    $newValue = sprintf("[%s,%s]", floor($match / 2), ceil($match / 2));
                    $number = substr_replace($number, $newValue, $pos, $matchLen);
                    continue 2;
                }
            }
        }

        // Still here? then no reduction action was needed. We're done
        break;
    }

    return $number;
}

function add(string $number1, string $number2)
{
    return sprintf('[%s,%s]', $number1, $number2);
}

function getMagnitude(string $number): int
{
    $pairRegex = '/\[([0-9]+),([0-9]+)\]/';
    if (is_numeric($number)) {
        return (int) $number;
    }
    $matches = [];
    preg_match($pairRegex, $number, $matches);
//    var_dump($matches);
    $replacement = (int)$matches[1] * 3 + (int)$matches[2] * 2;
//    var_dump('************', $matches, $replacement);
    $newNumber = substr_replace($number, (string)$replacement, strpos($number, $matches[0]), strlen($matches[0]));
    return getMagnitude($newNumber);
}