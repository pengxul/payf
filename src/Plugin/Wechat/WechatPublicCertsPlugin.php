<?php

declare(strict_types=1);

namespace Pengxul\Payf\Plugin\Wechat;

use Pengxul\Payf\Rocket;

class WechatPublicCertsPlugin extends GeneralPlugin
{
    protected function getMethod(): string
    {
        return 'GET';
    }

    protected function doSomething(Rocket $rocket): void
    {
        $rocket->setPayload(null);
    }

    protected function getUri(Rocket $rocket): string
    {
        return 'v3/certificates';
    }
}
