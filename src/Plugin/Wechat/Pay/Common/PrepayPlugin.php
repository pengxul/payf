<?php

declare(strict_types=1);

namespace Pengxul\Payf\Plugin\Wechat\Pay\Common;

use Pengxul\Payf\Exception\ContainerException;
use Pengxul\Payf\Exception\ServiceNotFoundException;
use Pengxul\Payf\Pay;
use Pengxul\Payf\Plugin\Wechat\GeneralPlugin;
use Pengxul\Payf\Rocket;

use function Pengxul\Payf\get_wechat_config;

class PrepayPlugin extends GeneralPlugin
{
    protected function getUri(Rocket $rocket): string
    {
        return 'v3/pay/transactions/jsapi';
    }

    protected function getPartnerUri(Rocket $rocket): string
    {
        return 'v3/pay/partner/transactions/jsapi';
    }

    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    protected function doSomething(Rocket $rocket): void
    {
        $config = get_wechat_config($rocket->getParams());

        $wechatId = $this->getWechatId($config, $rocket);

        if (!$rocket->getPayload()->has('notify_url')) {
            $wechatId['notify_url'] = $config['notify_url'] ?? null;
        }

        $rocket->mergePayload($wechatId);
    }

    protected function getWechatId(array $config, Rocket $rocket): array
    {
        $payload = $rocket->getPayload();
        $configKey = $this->getConfigKey($rocket->getParams());

        $result = [
            'appid' => $config[$configKey] ?? '',
            'mchid' => $config['mch_id'] ?? '',
        ];

        if (Pay::MODE_SERVICE === ($config['mode'] ?? null)) {
            $result = [
                'sp_appid' => $config[$configKey] ?? '',
                'sp_mchid' => $config['mch_id'] ?? '',
                'sub_mchid' => $payload->get('sub_mchid', $config['sub_mch_id'] ?? null),
            ];

            $subAppId = $payload->get('sub_appid', $config['sub_'.$configKey] ?? null);

            if (!empty($subAppId)) {
                $result['sub_appid'] = $subAppId;
            }
        }

        return $result;
    }
}
