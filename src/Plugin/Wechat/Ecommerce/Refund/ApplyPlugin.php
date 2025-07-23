<?php

declare(strict_types=1);

namespace Pengxul\Payf\Plugin\Wechat\Ecommerce\Refund;

use Pengxul\Payf\Exception\ContainerException;
use Pengxul\Payf\Exception\Exception;
use Pengxul\Payf\Exception\InvalidParamsException;
use Pengxul\Payf\Exception\ServiceNotFoundException;
use Pengxul\Payf\Plugin\Wechat\GeneralPlugin;
use Pengxul\Payf\Rocket;

use function Pengxul\Payf\get_wechat_config;

/**
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3_partner/apis/chapter7_6_1.shtml
 */
class ApplyPlugin extends GeneralPlugin
{
    /**
     * @throws InvalidParamsException
     */
    protected function getUri(Rocket $rocket): string
    {
        throw new InvalidParamsException(Exception::NOT_IN_SERVICE_MODE);
    }

    protected function getPartnerUri(Rocket $rocket): string
    {
        return 'v3/ecommerce/refunds/apply';
    }

    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    protected function doSomething(Rocket $rocket): void
    {
        $config = get_wechat_config($rocket->getParams());
        $payload = $rocket->getPayload();
        $key = $this->getConfigKey($rocket->getParams());

        $wechatId = [
            'sub_mchid' => $payload->get('sub_mchid', $config['sub_mch_id'] ?? ''),
            'sp_appid' => $payload->get('sp_appid', $config[$key] ?? ''),
        ];

        if (!$payload->has('notify_url')) {
            $wechatId['notify_url'] = $config['notify_url'] ?? null;
        }

        $rocket->mergePayload($wechatId);
    }
}
