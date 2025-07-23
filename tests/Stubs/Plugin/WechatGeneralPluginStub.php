<?php

namespace Pengxul\Payf\Tests\Stubs\Plugin;

use Pengxul\Payf\Plugin\Wechat\GeneralPlugin;
use Pengxul\Payf\Rocket;

class WechatGeneralPluginStub extends GeneralPlugin
{
    protected function doSomething(Rocket $rocket): void
    {
        $rocket->mergePayload(['config_key' => $this->getConfigKey($rocket->getParams())]);
    }

    protected function getUri(Rocket $rocket): string
    {
        return 'yansongda/pay';
    }

    protected function getPartnerUri(Rocket $rocket): string
    {
        return 'yansongda/pay/partner';
    }
}
