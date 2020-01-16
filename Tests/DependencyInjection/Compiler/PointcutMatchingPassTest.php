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

namespace JMS\AopBundle\Tests\DependencyInjection\Compiler;

use Exception;
use JMS\AopBundle\DependencyInjection\Compiler\PointcutMatchingPass;
use JMS\AopBundle\DependencyInjection\JMSAopExtension;
use JMS\AopBundle\Tests\DependencyInjection\Compiler\Fixture\LoggingInterceptor;
use JMS\AopBundle\Tests\DependencyInjection\Compiler\Fixture\LoggingPointcut;
use JMS\AopBundle\Tests\DependencyInjection\Compiler\Fixture\TestService;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use Symfony\Component\DependencyInjection\Compiler\ResolveParameterPlaceHoldersPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Filesystem\Filesystem;

class PointcutMatchingPassTest extends TestCase
{
    private string $cacheDir;
    private Filesystem $fs;

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function testProcess()
    {
        $container = $this->getContainer();

        $container
            ->register('pointcut', LoggingPointcut::class)
            ->addTag('jms_aop.pointcut', array('interceptor' => 'interceptor'));
        $container
            ->register('interceptor', LoggingInterceptor::class);
        $container
            ->register('test', TestService::class);

        $this->process($container);

        /** @var TestService $service */
        $service = $container->get('test');
        $this->assertInstanceOf(TestService::class, $service);
        $this->assertTrue($service->add());
        $this->assertTrue($service->delete());
        $this->assertNull($service->optional());
        $service->nothing();
        $this->assertEquals([
            'delete',
            'optional',
            'nothing',
        ], $container->get('interceptor')->getLog());
    }

    protected function setUp(): void
    {
        $this->cacheDir = sys_get_temp_dir() . '/jms_aop_test';
        $this->fs = new Filesystem();

        if (is_dir($this->cacheDir)) {
            $this->fs->remove($this->cacheDir);
        }

        $this->fs->mkdir($this->cacheDir, 0777);
    }

    protected function tearDown(): void
    {
        $this->fs->remove($this->cacheDir);
    }

    /**
     * @return ContainerBuilder
     * @throws Exception
     */
    private function getContainer(): ContainerBuilder
    {
        $container = new ContainerBuilder();

        $extension = new JMSAopExtension();
        $extension->load([[
            'cache_dir' => $this->cacheDir,
        ]], $container);

        return $container;
    }

    /**
     * @param ContainerBuilder $container
     * @throws ReflectionException
     */
    private function process(ContainerBuilder $container): void
    {
        $pass = new ResolveParameterPlaceHoldersPass();
        $pass->process($container);

        $pass = new PointcutMatchingPass();
        $pass->process($container);
    }
}
