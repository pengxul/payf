<?php

declare(strict_types=1);

namespace Pengxul\Payf\Plugin\Wechat\Fund\Profitsharing;

use Pengxul\Payf\Exception\ContainerException;
use Pengxul\Payf\Exception\Exception;
use Pengxul\Payf\Exception\InvalidParamsException;
use Pengxul\Payf\Exception\ServiceNotFoundException;
use Pengxul\Payf\Pay;
use Pengxul\Payf\Plugin\Wechat\GeneralPlugin;
use Pengxul\Payf\Rocket;

use function Pengxul\Payf\get_wechat_config;

/**
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter8_1_2.shtml
 */
class QueryPlugin extends GeneralPlugin
{
    protected function getMethod(): string
    {
        return 'GET';
    }

    protected function doSomething(Rocket $rocket): void
    {
        $rocket->setPayload(null);
    }

    /**
     * @throws ContainerException
     * @throws InvalidParamsException
     * @throws ServiceNotFoundException
     */
    protected function getUri(Rocket $rocket): string
    {
        $payload = $rocket->getPayload();
        $config = get_wechat_config($rocket->getParams());

        if (!$payload->has('out_order_no') || !$payload->has('transaction_id')) {
            throw new InvalidParamsException(Exception::MISSING_NECESSARY_PARAMS);
        }

        $url = 'v3/profitsharing/orders/'.
            $payload->get('out_order_no').
            '?transaction_id='.$payload->get('transaction_id');

        if (Pay::MODE_SERVICE === ($config['mode'] ?? null)) {
            $url .= '&sub_mchid='.$payload->get('sub_mchid', $config['sub_mch_id'] ?? '');
        }

        return $url;
    }
}
