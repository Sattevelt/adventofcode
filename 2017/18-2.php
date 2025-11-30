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
$test = explode("\n", 'snd 1
snd 2
snd p
rcv a
rcv b
rcv c
rcv d');
//$input = $test;

$work = [
    0 => [
        'p' => new Process(0, $input),
        'q' => [],
    ],
    1 => [
        'p' => new Process(1, $input),
        'q' => []
    ],
];

$cur = 0;
$oth = 1;
$sends = 0;

while (true) {
    $curWrk = $work[$cur]['p'];
    while (($snd = $curWrk->getNext()) !== null) {
        $work[$oth]['q'][] = $snd;
        if ($cur === 1) {
            $sends++;
        }
    };

    if (($val = array_shift($work[$cur]['q'])) !== null &&
        ($snd = $curWrk->getNext($val)) !== null
    ) {
        $work[$oth]['q'][] = $snd;
        if ($cur === 1) {
            $sends++;
        }
        continue;
    }
    if (count($work[1]['q']) === 0 && count($work[0]['q']) === 0) {
        break;
    }

    $oth = $cur;
    $cur = abs($cur - 1);
}
print_r($work);
echo "\n";
print_r($sends);
echo "\n";


class Process
{
    private $p;
    private $instr;
    private $register = [];
    private $pos = 0;
    private $regex = '/^([a-z]{3}) (\-?[a-z0-9]+)( (\-?[a-z0-9]+))?$/';

    public function __construct($p, $instr)
    {
        $this->p = $p;
        $this->instr = $instr;
        $this->register['p'] = $p;
    }

    public function getNext($rec = null)
    {
        while (true) {
            $matches = [];
            preg_match($this->regex, $this->instr[$this->pos],$matches);

            $action = $matches[1];
            $matchX = $matches[2];
            $valueX = 0;
            if (is_numeric($matchX)) {
                $valueX = (int) $matchX;
            } elseif (! isset($this->register[$matchX])) {
                $this->register[$matchX] = 0;
            } else {
                $valueX = $this->register[$matchX];
            }


            $matchY = isset($matches[4]) ? $matches[4] : null;
            $valueY = 0;
            if (is_numeric($matchY)) {
                $valueY = (int) $matchY;
            } elseif (! isset($this->register[$matchY]) && $matchY !== null) {
                $this->register[$matchY] = 0;
            } elseif ($matchY !== null) {
                $valueY = $this->register[$matchY];
            }

            switch ($action) {
                case 'snd':
                    $this->pos++;
                    return $valueX;
                    break;
                case 'set':
                    $this->register[$matchX] = $valueY;
                    break;
                case 'add':
                    $this->register[$matchX] = $valueX + $valueY;
                    break;
                case 'mul':
                    $this->register[$matchX] = $valueX * $valueY;
                    break;
                case 'mod':
                    $this->register[$matchX] = $valueX % $valueY;
                    break;
                case 'rcv':
                    if ($rec !== null) {
                        $this->register[$matchX] = $rec;
                        $rec = null;
                    } else {
                        return null;
                    }
                    break;
                case 'jgz':
                    if ($valueX > 0) {
                        $this->pos += $valueY - 1;
                    }
                    break;
            }
            $this->pos++;
        }
    }
}