<?php

declare(strict_types=1);

/**
 * Class Config
 */
class Config
{
    private static array $data = [
        'aocUrlFormat' => 'https://adventofcode.com/%s/day/%s/input',
        'aocSessionId' => '53616c7465645f5f04e351fa323b6994926e64a5e71567bcb78be564ddf2b9b586a6e47989712587871c503c9a7e73eed1d9d0f1e26f0759da64d14dfd7f197a',
        'localPathParts' => ['events', '%year%', '%day%'],
        'fileNamePart' => 'part-%part%.php',
        'basePath' => '.',
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
