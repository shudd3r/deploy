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


class GitRepository
{
    private string $directory;

    public function __construct(string $directory)
    {
        $this->directory = $directory;
    }

    public function exists(): bool
    {
        if (!is_dir($this->directory)) { return false; }
        chdir($this->directory);
        return trim(shell_exec('git rev-parse --is-inside-git-dir')) === 'true';
    }
}
