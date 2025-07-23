<?php

namespace Pengxul\Payf\Tests\Stubs\Plugin;

use Pengxul\Payf\Plugin\Alipay\GeneralPlugin;

class AlipayGeneralPluginStub extends GeneralPlugin
{
    protected function getMethod(): string
    {
        return 'yansongda';
    }
}
