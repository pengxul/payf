<?php

declare(strict_types=1);

namespace Pengxul\Payf\Plugin\Wechat\Pay\Pos;

use Pengxul\Payf\Plugin\Wechat\GeneralV2Plugin;
use Pengxul\Payf\Rocket;

/**
 * @see https://pay.weixin.qq.com/wiki/doc/api/micropay.php?chapter=9_5
 */
class QueryRefundPlugin extends GeneralV2Plugin
{
    protected function getUri(Rocket $rocket): string
    {
        return 'pay/refundquery';
    }
}
