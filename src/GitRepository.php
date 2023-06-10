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


class GitRepository
{
    private string $directory;

    public function __construct(string $directory)
    {
        $this->directory = $directory;
    }

    public function archive(string $ref, string $pathPrefix = ''): ?GitArchive
    {
        if (!$this->exists()) { return null; }

        $filename = sys_get_temp_dir() . '/deploy.zip';
        $prefix   = $pathPrefix ? ' --prefix=' . $pathPrefix . '/' : '';
        $command  = 'git archive ' . $ref . $prefix . ' --format zip --output ' . $filename;

        exec($command . ' 2>&1', $error);
        if ($error) {
            is_file($filename) && unlink($filename);
            return null;
        }

        $archive = new ZipArchive();
        $archive->open($filename);
        if (!$archive->numFiles) {
            unset($archive);
            is_file($filename) && unlink($filename);
            return null;
        }

        return new GitArchive($archive);
    }

    private function exists(): bool
    {
        if (!is_dir($this->directory)) { return false; }
        chdir($this->directory);
        return trim(shell_exec('git rev-parse --is-inside-git-dir')) === 'true';
    }
}
