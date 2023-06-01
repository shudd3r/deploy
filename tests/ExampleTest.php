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
use Shudd3r\Deploy\Example;


class ExampleTest extends TestCase
{
    public function testTestMethod_ReturnsCorrectString()
    {
        $example = new Example();
        $this->assertSame('Hello World!', $example->test());
    }
}