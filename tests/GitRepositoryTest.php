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
use Shudd3r\Deploy\GitRepository;


class GitRepositoryTest extends TestCase
{
    public function testArchiveMethod_ForLocationsOutsideGitRepository_ReturnsNull()
    {
        $repository = new GitRepository('Not a path');
        $this->assertNull($repository->archive('test'));

        $repository = new GitRepository(__DIR__ . '/Fixtures');
        $this->assertNull($repository->archive('test'));
    }

    public function testArchiveMethod_ForUnresolvedReference_ReturnsNull()
    {
        $repository = new GitRepository(__DIR__ . '/Fixtures/remote-repo');
        $this->assertNull($repository->archive('test'));
    }

    public function testArchiveMethod_ForResolvedReference_ReturnsGitArchiveInstance()
    {
        $repository = new GitRepository(__DIR__ . '/Fixtures/remote-repo');
        $this->assertInstanceOf(GitArchive::class, $repository->archive('develop'));
    }
}
