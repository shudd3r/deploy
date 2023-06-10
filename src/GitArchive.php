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
use InvalidArgumentException;


class GitArchive
{
    private ZipArchive $archive;

    public function __construct(ZipArchive $archive)
    {
        if (!$archive->numFiles) {
            throw new InvalidArgumentException();
        }
        $this->archive = $archive;
    }

    public function __destruct()
    {
        $filename = $this->archive->filename;
        $this->archive->close();
        unset($this->archive);
        unlink($filename);
    }

    public function numberOfFiles(): int
    {
        return $this->archive->numFiles;
    }
}
