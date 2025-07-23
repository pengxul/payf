<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Wechat\Pay\Combine;

use Pengxul\Pay\Plugin\Wechat\Pay\Common\CombinePrepayPlugin;
use Pengxul\Pay\Rocket;

/**
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter5_1_2.shtml
 */
class H5PrepayPlugin extends CombinePrepayPlugin
{
    protected function getUri(Rocket $rocket): string
    {
        return 'v3/combine-transactions/h5';
    }
}
