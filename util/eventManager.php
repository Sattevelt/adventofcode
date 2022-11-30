<?php

declare(strict_types=1);

require_once 'config.php';

/**
 * Class EventManager
 */
class EventManager
{
    public function createPuzzle(string $year, string $day, bool $overwriteExisting = false)
    {
        $sep = DIRECTORY_SEPARATOR;

        // Handle basepath
        $basePath = Config::get('basePath');
        $this->checkPath($basePath);

        // Create dir structure
        $localPathParts = str_replace(['%year%', '%day%'], [$year, $day], Config::get('localPathParts'));
        $localPath = $basePath;
        foreach ($localPathParts as $localPathPart) {
            $localPath .= DIRECTORY_SEPARATOR . $localPathPart;
            $this->createDirIfNotExists($localPath);
        }

        // Create puzzle file names
        $inputFile = $localPath . $sep . 'input.txt';
        $testsFile = $localPath . $sep . 'tests.php';
        $partAFile = $localPath . $sep . 'part-a.php';
        $partBFile = $localPath . $sep . 'part-b.php';

        // Get file contents
        $inputData = $this->downloadInput((int)$year, (int)$day);
        $partData = file_get_contents($basePath . $sep . 'util' . $sep . 'defaultCode.php');
        $testData = file_get_contents($basePath . $sep . 'util' . $sep . 'defaultTests.php');

        // Write files
        $this->createFileIfNotExists($inputFile, $inputData, $overwriteExisting);
        $this->createFileIfNotExists($testsFile, $testData, $overwriteExisting);
        $this->createFileIfNotExists($partAFile, $partData, $overwriteExisting);
        $this->createFileIfNotExists($partBFile, $partData, $overwriteExisting);
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
        if (! $aocSessionId) {
            return '';
        }
        $url = sprintf(Config::get('aocUrlFormat'), $year, $day);
        $options = [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_COOKIE => 'session=' . Config::get('aocSessionId'),
        ];

        $curl = curl_init($url);
        curl_setopt_array($curl, $options);
        $result = curl_exec($curl);
        curl_close($curl);

        if (is_string($result)) {
            return $result;
        }

        return '';
    }
}
