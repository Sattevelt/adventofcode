<?php

spl_autoload_register(function ($class) {
    $replacements = [
        'AOC\\' => '',
        '\\' => DIRECTORY_SEPARATOR,
    ];

    $file = 'src' . DIRECTORY_SEPARATOR . str_replace(array_keys($replacements), array_values($replacements), $class).'.php';
    if (file_exists($file)) {
        require $file;
        return true;
    }
    var_dump($file);
    return false;
});

use AOC\Util\App;
use AOC\Util\Config;

Config::set('basePath', __DIR__);
// Set the session id if you want to automatically download puzzle inputs for assignments.
// Can easily be taken from your browser cookies when logged in to adventofcode.com
Config::set('aocSessionId', '53616c7465645f5f2683533f99610f1ba5a81184a927bff2f762b0aa59e005f3df791e2e03c40abb4373ef648c74ccab6e6cdd7be58c5119ceffae0bcb712f2c');

App::run($_SERVER['argv']);
