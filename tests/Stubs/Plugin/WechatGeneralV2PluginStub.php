<?php

namespace Pengxul\Payf\Tests\Stubs\Plugin;

use Pengxul\Payf\Plugin\Wechat\GeneralV2Plugin;
use Pengxul\Payf\Rocket;

class WechatGeneralV2PluginStub extends GeneralV2Plugin
{
    protected function getUri(Rocket $rocket): string
    {
        return 'yansongda/pay';
    }
}
