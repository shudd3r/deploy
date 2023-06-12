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


class GitArchiveTest extends TestCase
{
    use Fixtures\FileSystemMethods;

    public function testInstance_WithNotFilePath_ReturnsNull()
    {
        $this->assertNull(GitArchive::instance(__DIR__ . '/file-not-exists.zip'));
    }

    public function testInstance_WithEmptyFile_ReturnsNull()
    {
        $this->assertNull(GitArchive::instance($this->tempFile()));
    }

    public function testInstance_WithInvalidArchiveFile_ReturnsNull()
    {
        $this->assertNull(GitArchive::instance($this->createFile('not archive contents')));
    }

    public function testInstance_ArchiveFile_IsRemovedWithObjectReference()
    {
        $archiveFile = $this->createArchive(['a.txt' => 'aaa']);

        $this->assertInstanceOf(GitArchive::class, $archive = GitArchive::instance($archiveFile));
        $this->assertFileExists($archiveFile);

        unset($archive);
        $this->assertFileDoesNotExist($archiveFile);
    }

    /**
     * @dataProvider exampleArchiveFiles
     */
    public function testNumberOfFiles_ReturnsNumberOfFilesInArchive(array $files)
    {
        $archive = GitArchive::instance($this->createArchive($files));
        $this->assertSame(count($files), $archive->numberOfFiles());
    }

    /**
     * @dataProvider exampleArchiveFiles
     */
    public function testExtractToMethod_CreatesFilesInGivenDirectory(array $files)
    {
        $archive = GitArchive::instance($this->createArchive($files));
        $archive->extractTo($target = $this->tempDir());
        foreach ($files as $file => $contents) {
            $filename = $target . DIRECTORY_SEPARATOR . $file;
            $this->assertStringEqualsFile($filename, $contents);
        }
    }

    public static function exampleArchiveFiles(): array
    {
        return [
            [['a.txt' => 'a contents']],
            [['a.txt' => 'aaa', 'b.txt' => 'bbb']],
            [['foo.txt' => 'this is foo', 'foo/bar.txt' => 'this is bar', 'dir/baz.txt' => 'baz contents']]
        ];
    }
}
