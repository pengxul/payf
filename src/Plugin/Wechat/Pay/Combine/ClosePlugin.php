<?php

declare(strict_types=1);

namespace Pengxul\Payf\Plugin\Wechat\Pay\Combine;

use Pengxul\Payf\Exception\ContainerException;
use Pengxul\Payf\Exception\Exception;
use Pengxul\Payf\Exception\InvalidParamsException;
use Pengxul\Payf\Exception\ServiceNotFoundException;
use Pengxul\Payf\Rocket;
use Yansongda\Supports\Collection;

use function Pengxul\Payf\get_wechat_config;

/**
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter5_1_12.shtml
 */
class ClosePlugin extends \Pengxul\Payf\Plugin\Wechat\Pay\Common\ClosePlugin
{
    protected function getUri(Rocket $rocket): string
    {
        $payload = $rocket->getPayload();

        if (!$payload->has('combine_out_trade_no') && !$payload->has('out_trade_no')) {
            throw new InvalidParamsException(Exception::MISSING_NECESSARY_PARAMS);
        }

        return 'v3/combine-transactions/out-trade-no/'.
            $payload->get('combine_out_trade_no', $payload->get('out_trade_no')).
            '/close';
    }

    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    protected function doSomething(Rocket $rocket): void
    {
        $config = get_wechat_config($rocket->getParams());

        $rocket->setPayload(new Collection([
            'combine_appid' => $config['combine_appid'] ?? '',
            'sub_orders' => $rocket->getParams()['sub_orders'] ?? [],
        ]));
    }
}
