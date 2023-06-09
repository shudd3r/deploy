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
    public function testExistsMethod_ForNotExistingArchiveFile_ReturnsFalse()
    {
        $archive = new GitArchive(__DIR__ . '/foo.txt');
        $this->assertFalse($archive->exists());
    }

    public function testExistsMethod_ForExistingArchiveFile_ReturnsTrue()
    {
        $filename = sys_get_temp_dir() . '/tests.txt';
        file_put_contents($filename, 'xxx');
        $archive = new GitArchive($filename);
        $this->assertTrue($archive->exists());
    }

    public function testFileIsRemovedAfterArchiveObjectIsDestroyed()
    {
        $filename = sys_get_temp_dir() . '/tests.txt';
        file_put_contents($filename, 'xxx');
        $archive = new GitArchive($filename);
        $this->assertTrue($archive->exists());
        $this->assertTrue(is_file($filename));
        unset($archive);
        $this->assertFalse(is_file($filename));
    }
}
