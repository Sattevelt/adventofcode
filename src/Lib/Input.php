<?php

namespace AOC\Lib;

class Input
{
    public static function inputAsArray(string $input): array
    {
        return explode("\n", trim($input));
    }
}