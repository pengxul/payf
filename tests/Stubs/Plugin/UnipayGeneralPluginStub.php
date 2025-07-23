<?php

namespace Pengxul\Payf\Tests\Stubs\Plugin;

use Pengxul\Payf\Plugin\Unipay\GeneralPlugin;
use Pengxul\Payf\Rocket;

class UnipayGeneralPluginStub extends GeneralPlugin
{
    protected function doSomething(Rocket $rocket): void
    {
    }

    protected function getUri(Rocket $rocket): string
    {
        return 'yansongda/pay';
    }
}

class UnipayGeneralPluginStub1 extends GeneralPlugin
{
    protected function doSomething(Rocket $rocket): void
    {
    }

    protected function getUri(Rocket $rocket): string
    {
        return 'https://yansongda.cn/pay';
    }
}
