<?php

declare(strict_types=1);

namespace AOC\Util;

/**
 * Class Config
 */
class Config
{
    private static array $data = [
        'aocUrlFormat' => 'https://adventofcode.com/%s/day/%s/input',
        'aocSessionId' => '53616c7465645f5f2683533f99610f1ba5a81184a927bff2f762b0aa59e005f3df791e2e03c40abb4373ef648c74ccab6e6cdd7be58c5119ceffae0bcb712f2c',
        'localPathParts' => ['events', '%year%', '%day%'],
        'fileNamePart' => '%part%.php',
        'basePath' => './src',
    ];

    public static function set(string $key, string $value): void
    {
        self::$data[$key] = $value;
    }

    public static function get(string $key)
    {
        return self::$data[$key] ?? '';
    }
}
