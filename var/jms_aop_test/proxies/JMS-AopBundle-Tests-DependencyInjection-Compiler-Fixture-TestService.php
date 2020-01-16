<?php

namespace EnhancedProxyd9f52836_b7de18c96fff0809b940d44d33c1d843648df836\__CG__\JMS\AopBundle\Tests\DependencyInjection\Compiler\Fixture;

/**
 * CG library enhanced proxy class.
 *
 * This code was generated automatically by the CG library, manual changes to it
 * will be lost upon next generation.
 */
class TestService extends \JMS\AopBundle\Tests\DependencyInjection\Compiler\Fixture\TestService
{
    private $__CGInterception__loader;

    public function delete(): bool
    {
        $ref = new \ReflectionMethod('JMS\\AopBundle\\Tests\\DependencyInjection\\Compiler\\Fixture\\TestService', 'delete');
        $interceptors = $this->__CGInterception__loader->loadInterceptors($ref, $this, array());
        $invocation = new \CG\Proxy\MethodInvocation($ref, $this, array(), $interceptors);

        return $invocation->proceed();
    }

    public function __CGInterception__setLoader(\CG\Proxy\InterceptorLoaderInterface $loader)
    {
        $this->__CGInterception__loader = $loader;
    }
}