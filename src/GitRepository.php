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

    public function archive(string $ref, string $subdirectory = ''): ?GitArchive
    {
        if (!$this->exists()) { return null; }

        $filename = $ref . '.zip';
        $archive  = new GitArchive($this->directory . '/' . $filename);
        $prefix   = $subdirectory ? ' --prefix=' . $subdirectory . '/' : '';
        $command  = 'git archive ' . $ref . $prefix . ' --format zip --output ' . $filename;

        exec($command . ' 2>&1', $error);
        return $error ? null : $archive;
    }

    private function exists(): bool
    {
        if (!is_dir($this->directory)) { return false; }
        chdir($this->directory);
        return trim(shell_exec('git rev-parse --is-inside-git-dir')) === 'true';
    }
}
