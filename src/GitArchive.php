<?php declare(strict_types=1);

/*
 * This file is part of Shudd3r/Deploy package.
 *
 * (c) Shudd3r <q3.shudder@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Shudd3r\Deploy;

use ZipArchive;


class GitArchive
{
    private ZipArchive $archive;

    private function __construct(ZipArchive $archive)
    {
        $this->archive = $archive;
    }

    public static function instance(string $filename): ?self
    {
        if (!is_file($filename) || filesize($filename) === 0) { return null; }

        $archive = new ZipArchive();
        return $archive->open($filename) === true ? new self($archive) : null;
    }

    public function __destruct()
    {
        $filename = $this->archive->filename;
        $this->archive->close();
        unset($this->archive);
        unlink($filename);
    }

    public function fileList(): array
    {
        $files = [];
        $idx   = 0;
        while ($filename = $this->archive->getNameIndex($idx++)) {
            $files[] = $filename;
        }

        return $files;
    }

    public function numberOfFiles(): int
    {
        return $this->archive->numFiles;
    }

    public function extractTo(string $directory): void
    {
        $this->archive->extractTo($directory);
    }
}
