<?php

declare(strict_types=1);

namespace AOC\Util;

use DateTime;
use Exception;

require_once 'Config.php';
require_once 'EventManager.php';

/**
 * Class App
 */
class App
{
    public static function run(array $args)
    {
        try {
            switch ($args[1] ?? null) {
                case 'create':
                    self::doCreate($args);
                    break;
                case 'run':
                    self::doRun($args);
                    break;
                case 'help':
                    self::doHelp();
                    break;
                default:
                    throw new Exception('Unknown command as first argument');
            }
        } catch (Exception $e) {
            echo 'Application encountered an error with message: ' . $e->getMessage();
        }
    }

    private static function doHelp()
    {
        echo <<<END
Use this application to easily run create new entries or run scripts for Advent Of Code assignments.
Uses the following folder structure:
|- events
|  - [year]
|    - [day]
|- Util

** create **

Create a new set of files needed to complete an assignment in the appropriate folder structure for the given year and day. Generates:
- 1.php: script to solve the first part of the assignment. Filled with content from ./Util/defaultCode.php
- 2.php: script to solve the second part of the assignment. Filled with content from ./Util/defaultCode.php
- input.txt: input data to be used in both parts. Automatically downloaded from adventofcode.com is config value is set.
- tests.php: A place where you can place your test inputs and expected answers 

Takes two optional arguments to indicate for what year and day files need to created.

Ex: php App.php create 2022 12

Will create a set of files for the twelfth assignment of the 2022 event, stored in events/2022/12/.

It is possible to overwrite existing files by adding 'overwrite' as a final argument. This will overwrite all of the three mentioned files with new content. Will download the input data again from adventofcode.com.

Ex: php App.php create 2022 12 overwrite.


** run **

Run a script for the given year, day and part indicator.
Can accept al values as one string, or separated as individual arguments in order.
All examples below will attempt to run the second part of the first day assignment of the 2022 event.
php App.php run 2022 1 1 // Run first part of the first assignment of the 2022 event
php App.php run 2023 13 2 // Run second part of the 13th assignment of the 2023 event.

Running tests is as easy as adding a 'test' parameter at the end of your run command:
php App.php run 2022 1 1 test

** help **

Show this help output

END;
        die;
    }

    private static function doCreate(array $args)
    {
        $em = new EventManager();
        $year = $args[2] ?? 0;
        $day = $args[3] ?? 0;
        $overwrite = $args[4] ?? '';
        list($year, $day) = self::prepareDateAndYear($year, $day);

        $em->createPuzzle($year, $day, $overwrite === 'overwrite');
    }

    private static function doRun(array $args)
    {
        $year = (int)$args[2] ?? 0;
        $day = (int)$args[3] ?? 0;
        $part = (int)$args[4] ?? 0;
        $runtests = ($args[5] ?? '') === 'test';

        $sep = DIRECTORY_SEPARATOR;
        $localPathParts = $localPathParts = str_replace(
            ['%year%', '%day%'],
            [$year, $day],
            Config::get('localPathParts')
        );
        $localPath = Config::get('basePath') . $sep .implode($sep, $localPathParts);
        $filename = str_replace('%part%', (string)$part, Config::get('fileNamePart'));
        $file =  $localPath . $sep . $filename;
        if (!file_exists($file)) {
            throw new Exception(sprintf("File to include not found: '%s'", $file));
        }

        if ($runtests) {
            $tests = require_once($localPath . $sep . $part . 'tests.php');
            foreach ($tests as $test) {
                $input = $test['input'];
                $result = include($file);
                echo "************************************\n";
                echo sprintf("Running test: %s\n", $test['name']);
                echo sprintf("Expecting: %s\n", $test['solution']);
                echo sprintf("Got: %s\n", $result);
                echo sprintf("Test result: %s\n", $test['solution'] === $result ? 'PASS' : 'FAIL');
            }
            echo "************************************\n";
        } else {
            $input = file_get_contents($localPath . $sep . 'input.txt');
            echo sprintf("Solution: %s\n", include($file));
        }
    }

    private static function prepareDateAndYear(string $year, string $day): array
    {
        $now = new DateTime();
        if ($day < 1 || $day > 31) {
            throw new Exception('Day must be int between 1 and 31 including.');
        }
        $day = (string)(int)$day; // Remove leading zero.

        if ($year < 2000 || $year > 3000) {
            throw new Exception('Year must be bewteen 2000 and 3000');
        }

        return [$year, $day];
    }
}
