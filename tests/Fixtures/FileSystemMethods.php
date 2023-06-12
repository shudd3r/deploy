<?php

/*
 * This file is part of Shudd3r/Deploy package.
 *
 * (c) Shudd3r <q3.shudder@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Shudd3r\Deploy\Tests\Fixtures;

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use FilesystemIterator;
use ZipArchive;


trait FileSystemMethods
{
    private static array $tmpFiles       = [];
    private static array $tmpDirectories = [];

    protected function tearDown(): void
    {
        foreach (self::$tmpFiles as $filename) {
            is_file($filename) && unlink($filename);
        }

        foreach (self::$tmpDirectories as $directory) {
            self::removeDirectory($directory);
        }

        self::$tmpFiles       = [];
        self::$tmpDirectories = [];
    }

    private function createArchive(array $fileContents = []): string
    {
        $zip = new ZipArchive();
        $zip->open($filename = $this->tempFile(), ZipArchive::CREATE);
        foreach ($fileContents as $file => $contents) {
            $zip->addFromString($file, $contents);
        }
        $zip->close();

        return $filename;
    }

    private function createFile(string $contents): string
    {
        file_put_contents($filename = $this->tempFile(), $contents);
        return $filename;
    }

    private function tempFile(): string
    {
        return self::$tmpFiles[] = tempnam(sys_get_temp_dir(), 'git');
    }

    private function tempDir(): string
    {
        return self::$tmpDirectories[] = sys_get_temp_dir() . DIRECTORY_SEPARATOR . bin2hex(random_bytes(3));
    }

    private static function removeDirectory(string $directory)
    {
        $nodes = new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS);
        $nodes = new RecursiveIteratorIterator($nodes, RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($nodes as $node) {
            $path = $node->getPathname();
            $node->isDir() ? rmdir($path) : unlink($path);
        }
        rmdir($directory);
    }
}
