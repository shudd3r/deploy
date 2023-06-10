<?php declare(strict_types=1);

/*
 * This file is part of Shudd3r/Deploy package.
 *
 * (c) Shudd3r <q3.shudder@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Shudd3r\Deploy\Tests;

use PHPUnit\Framework\TestCase;
use Shudd3r\Deploy\GitArchive;
use ZipArchive;
use InvalidArgumentException;


class GitArchiveTest extends TestCase
{
    public function testInstanceWithNotInitializedArchive_ThrowsException()
    {
        $zip = new ZipArchive();

        $this->expectException(InvalidArgumentException::class);
        new GitArchive($zip);
    }

    public function testInstanceWithEmptyArchiveFile_ThrowsExceptionAndFileIsRemoved()
    {
        $zip = new ZipArchive();
        $zip->open($filename = $this->tempFile());

        try {
            new GitArchive($zip);
        } catch (InvalidArgumentException $e) {
            $zip->close();
            $this->assertFileDoesNotExist($filename);
        }
    }

    public function testInstanceWithInvalidArchiveFile_ThrowsExceptionAndFileIsNotRemoved()
    {
        file_put_contents($filename = $this->tempFile(), 'not archive contents');
        $zip = new ZipArchive();
        $zip->open($filename);

        try {
            new GitArchive($zip);
        } catch (InvalidArgumentException $e) {
            $this->assertFileExists($filename);
            unlink($filename);
        }
    }

    public function testExistingArchiveFile_IsRemovedWithObjectReference()
    {
        $this->createArchive($filename = $this->tempFile(), ['a.txt' => 'aaa']);
        $zip = new ZipArchive();
        $zip->open($filename);

        $archive = new GitArchive($zip);
        $this->assertFileExists($filename);

        unset($archive);
        $this->assertFileDoesNotExist($filename);
    }

    /**
     * @dataProvider exampleArchiveFiles
     */
    public function testNumberOfFiles_ReturnsNumberOfFilesInArchive(array $files)
    {
        $this->createArchive($filename = $this->tempFile(), $files);
        $zip = new ZipArchive();
        $zip->open($filename);

        $archive = new GitArchive($zip);
        $this->assertSame(count($files), $archive->numberOfFiles());
    }

    public static function exampleArchiveFiles(): array
    {
        return [
            [['a.txt' => 'a contents']],
            [['a.txt' => 'aaa', 'b.txt' => 'bbb']],
            [['foo.txt' => 'this is foo', 'foo/bar.txt' => 'this is bar', 'dir/baz.txt' => 'baz contents']]
        ];
    }

    private function createArchive(string $filename, array $fileContents = []): void
    {
        $zip = new ZipArchive();
        $zip->open($filename, ZipArchive::CREATE);
        foreach ($fileContents as $file => $contents) {
            $zip->addFromString($file, $contents);
        }
        $zip->close();
    }

    private function tempFile(): string
    {
        return tempnam(sys_get_temp_dir(), 'git');
    }
}
