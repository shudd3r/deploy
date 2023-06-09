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
use Shudd3r\Deploy\GitRepository;


class GitRepositoryTest extends TestCase
{
    public function testExists_ForLocationsOutsideGitRepository_ReturnsFalse()
    {
        $repository = new GitRepository('Not a path');
        $this->assertFalse($repository->exists());

        $repository = new GitRepository(__DIR__ . '/Fixtures');
        $this->assertFalse($repository->exists());
    }

    public function testExists_InGitRepository_ReturnsTrue()
    {
        $repository = new GitRepository(__DIR__ . '/Fixtures/remote-repo');
        $this->assertTrue($repository->exists());
    }
}
