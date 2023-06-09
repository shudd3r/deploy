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


class GitArchive
{
    private string $filename;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    public function __destruct()
    {
        if (is_file($this->filename)) { unlink($this->filename); }
    }

    public function exists(): bool
    {
        return is_file($this->filename);
    }
}
