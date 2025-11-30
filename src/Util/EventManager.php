<?php

declare(strict_types=1);

namespace AOC\Util;

use AOC\Util\Config;
use Exception;

/**
 * Class EventManager
 */
class EventManager
{
    public function createPuzzle(string $year, string $day, bool $overwriteExisting = false)
    {
        $sep = DIRECTORY_SEPARATOR;

        // Handle basepaths
        $srcPath = Config::get('basePath') . $sep . 'src';
        $destPath = Config::get('basePath');

        $this->checkPath($srcPath);
        $this->checkPath($destPath);

        // Create dir structure
        $localPathParts = str_replace(['%year%', '%day%'], [$year, $day], Config::get('localPathParts'));
        foreach ($localPathParts as $localPathPart) {
            $destPath .= DIRECTORY_SEPARATOR . $localPathPart;
            $this->createDirIfNotExists($destPath);
        }

        $inputData = $this->downloadInput((int)$year, (int)$day);
        $partData = file_get_contents($srcPath . $sep . 'Util' . $sep . 'defaultCode.php');
        $testData = file_get_contents($srcPath . $sep . 'Util' . $sep . 'defaultTests.php');

        // Create puzzle file names and content
        $fileDefs = [
            [
                'filename' => 'input.txt',
                'content' => $inputData
            ],
            [
                'filename' => '1.php',
                'content' => $partData
            ],
            [
                'filename' => '1tests.php',
                'content' => $testData
            ],
            [
                'filename' => '2.php',
                'content' => $partData
            ],
            [
                'filename' => '2tests.php',
                'content' => $testData
            ],
        ];

        // write files
        foreach ($fileDefs as $fileDef) {
            $this->createFileIfNotExists($destPath . $sep . $fileDef['filename'], $fileDef['content'], $overwriteExisting);
        }
    }

    private function createDirIfNotExists(string $dirName)
    {
        if (!is_dir($dirName)) {
            if (!mkdir($dirName)) {
                throw new Exception(sprintf("Failed creating dir '%s'", $dirName));
            } else {
                echo sprintf("- created dir: %s\n", $dirName);
            }
        } else {
            echo sprintf("- skipped creating dir: %s\n", $dirName);
        }
        if (!is_writable($dirName)) {
            throw new Exception(sprintf("Directory '%s' is not writable.", $dirName));
        }
    }

    private function createFileIfNotExists(string $fileName, string $content, bool $overwriteExisting = false): void
    {
        if (!file_exists($fileName) || $overwriteExisting) {
            file_put_contents($fileName, $content);
            echo sprintf("- created file: %s\n", $fileName);
        } else {
            echo sprintf("- skipped creating file: %s (use argument -o to overwrite).\n", $fileName);
        }
    }

    private function checkPath($path): void
    {
        if (!is_dir($path) || !is_writable($path)) {
            throw new Exception("Path '%s' is not a directory or not writable.", $path);
        }
    }

    private function downloadInput(int $year, int $day): string
    {
        $aocSessionId = Config::get('aocSessionId');
        if (!$aocSessionId) {
            return '';
        }
        $url = sprintf(Config::get('aocUrlFormat'), $year, $day);
        $options = [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_COOKIE => 'session=' . Config::get('aocSessionId'),
            CURLOPT_USERAGENT => 'https://github.com/Sattevelt/adventofcode sattevelt@gmail.com',
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0
        ];

        $curl = curl_init($url);
        curl_setopt_array($curl, $options);
        $result = curl_exec($curl);

        if (is_string($result)) {
            return $result;
        }

        return '';
    }
}
