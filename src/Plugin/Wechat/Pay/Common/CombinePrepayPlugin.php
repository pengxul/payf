<?php

declare(strict_types=1);

namespace Pengxul\Payf\Plugin\Wechat\Pay\Common;

use Pengxul\Payf\Exception\ContainerException;
use Pengxul\Payf\Exception\ServiceNotFoundException;
use Pengxul\Payf\Plugin\Wechat\GeneralPlugin;
use Pengxul\Payf\Rocket;

use function Pengxul\Payf\get_wechat_config;

class CombinePrepayPlugin extends GeneralPlugin
{
    protected function getUri(Rocket $rocket): string
    {
        return 'v3/combine-transactions/jsapi';
    }

    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    protected function doSomething(Rocket $rocket): void
    {
        $config = get_wechat_config($rocket->getParams());
        $collection = $rocket->getPayload();

        $payload = $this->getWechatId($config);

        if (!$collection->has('notify_url')) {
            $payload['notify_url'] = $config['notify_url'] ?? '';
        }

        if (!$collection->has('combine_out_trade_no')) {
            $payload['combine_out_trade_no'] = $rocket->getParams()['out_trade_no'];
        }

        $rocket->mergePayload($payload);
    }

    protected function getWechatId(array $config): array
    {
        return [
            'combine_appid' => $config['combine_app_id'] ?? '',
            'combine_mchid' => $config['combine_mch_id'] ?? '',
        ];
    }
}
