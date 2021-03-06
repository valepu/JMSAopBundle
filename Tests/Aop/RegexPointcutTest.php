<?php

/*
 * Copyright 2011 Johannes M. Schmitt <schmittjoh@gmail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace JMS\AopBundle\Tests\Aop;

use JMS\AopBundle\Aop\RegexPointcut;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;

class RegexPointcutTest extends TestCase
{
    public function testMatchesClass()
    {
        $pointcut = new RegexPointcut('');
        $this->assertTrue($pointcut->matchesClass(new ReflectionClass('stdClass')));
    }

    public function testMatchesMethod()
    {
        $pointcut = new RegexPointcut('foo$');

        $method = new ReflectionMethod('JMS\AopBundle\Tests\Aop\RegexPointcutTestClass', 'foo');
        $this->assertTrue($pointcut->matchesMethod($method));

        $method = new ReflectionMethod('JMS\AopBundle\Tests\Aop\RegexPointcutTestClass', 'bar');
        $this->assertFalse($pointcut->matchesMethod($method));
    }
}

class RegexPointcutTestClass
{
    public function foo()
    {
    }

    public function bar()
    {
    }
}
