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
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter9_1_2.shtml
 */
class SendPlugin extends GeneralPlugin
{
    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    protected function doSomething(Rocket $rocket): void
    {
        $params = $rocket->getParams();
        $config = get_wechat_config($params);

        if (!$rocket->getPayload()->has('appid')) {
            $rocket->mergePayload(['appid' => $config[$this->getConfigKey($params)] ?? '']);
        }

        if (!$rocket->getPayload()->has('stock_creator_mchid')) {
            $rocket->mergePayload(['stock_creator_mchid' => $config['mch_id']]);
        }

        $rocket->getPayload()->forget('openid');
    }

    /**
     * @throws InvalidParamsException
     */
    protected function getUri(Rocket $rocket): string
    {
        $payload = $rocket->getPayload();

        if (!$payload->has('openid')) {
            throw new InvalidParamsException(Exception::MISSING_NECESSARY_PARAMS);
        }

        return 'v3/marketing/favor/users/'.$payload->get('openid').'/coupons';
    }
}
