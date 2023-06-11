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


class GitArchiveTest extends TestCase
{
    public function testInstance_WithNotFilePath_ReturnsNull()
    {
        $this->assertNull(GitArchive::instance(__DIR__ . '/file-not-exists.zip'));
    }

    public function testInstance_WithEmptyArchiveFile_ReturnsNull()
    {
        $this->assertNull(GitArchive::instance($filename = $this->tempFile()));
        unlink($filename);
    }

    public function testInstance_WithInvalidArchiveFile_ReturnsNull()
    {
        file_put_contents($filename = $this->tempFile(), 'not archive contents');
        $this->assertNull(GitArchive::instance($filename));
        unlink($filename);
    }

    public function testInstance_ArchiveFile_IsRemovedWithObjectReference()
    {
        $this->createArchive($filename = $this->tempFile(), ['a.txt' => 'aaa']);

        $this->assertInstanceOf(GitArchive::class, $archive = GitArchive::instance($filename));
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

        $archive = GitArchive::instance($filename);
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
