<?php

declare(strict_types=1);

/**
 * Class Config
 */
class Config
{
    private static array $data = [
        'aocUrlFormat' => 'https://adventofcode.com/%s/day/%s/input',
        'aocSessionId' => '',
        'localPathParts' => ['events', '%year%', '%day%'],
        'fileNamePart' => 'part-%part%.php',
        'basePath' => '.',
    ];

    public static function set(string $key, string $value): void
    {
        self::$data[$key] = $value;
    }

    public static function get(string $key): mixed
    {
        return self::$data[$key] ?? '';
    }
}
