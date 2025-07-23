<?php

declare(strict_types=1);

namespace Pengxul\Payf\Plugin\Wechat\Marketing\Coupon;

use Pengxul\Payf\Exception\ContainerException;
use Pengxul\Payf\Exception\Exception;
use Pengxul\Payf\Exception\InvalidParamsException;
use Pengxul\Payf\Exception\ServiceNotFoundException;
use Pengxul\Payf\Plugin\Wechat\GeneralPlugin;
use Pengxul\Payf\Rocket;

use function Pengxul\Payf\get_wechat_config;

/**
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter9_1_13.shtml
 */
class PausePlugin extends GeneralPlugin
{
    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    protected function doSomething(Rocket $rocket): void
    {
        $config = get_wechat_config($rocket->getParams());
        $payload = $rocket->getPayload();

        if (!$payload->has('stock_creator_mchid')) {
            $rocket->mergePayload(['stock_creator_mchid' => $config['mch_id']]);
        }

        $rocket->getPayload()->forget('stock_id');
    }

    /**
     * @throws InvalidParamsException
     */
    protected function getUri(Rocket $rocket): string
    {
        $payload = $rocket->getPayload();

        if (!$payload->has('stock_id')) {
            throw new InvalidParamsException(Exception::MISSING_NECESSARY_PARAMS);
        }

        return 'v3/marketing/favor/stocks/'.$payload->get('stock_id').'/pause';
    }
}
