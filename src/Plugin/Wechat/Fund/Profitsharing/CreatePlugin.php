<?php

declare(strict_types=1);

namespace Pengxul\Payf\Plugin\Wechat\Fund\Profitsharing;

use Pengxul\Payf\Exception\ContainerException;
use Pengxul\Payf\Exception\InvalidConfigException;
use Pengxul\Payf\Exception\InvalidParamsException;
use Pengxul\Payf\Exception\InvalidResponseException;
use Pengxul\Payf\Exception\ServiceNotFoundException;
use Pengxul\Payf\Pay;
use Pengxul\Payf\Plugin\Wechat\GeneralPlugin;
use Pengxul\Payf\Rocket;
use Pengxul\Payf\Traits\HasWechatEncryption;
use Yansongda\Supports\Collection;

use function Pengxul\Payf\encrypt_wechat_contents;
use function Pengxul\Payf\get_wechat_config;

/**
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter8_1_1.shtml
 */
class CreatePlugin extends GeneralPlugin
{
    use HasWechatEncryption;

    /**
     * @throws ContainerException
     * @throws InvalidConfigException
     * @throws InvalidParamsException
     * @throws InvalidResponseException
     * @throws ServiceNotFoundException
     */
    protected function doSomething(Rocket $rocket): void
    {
        $payload = $rocket->getPayload();
        $params = $this->loadSerialNo($rocket->getParams());

        $extra = $this->getWechatExtra($params, $payload);
        $extra['receivers'] = $this->getReceivers($params);

        $rocket->setParams($params);
        $rocket->mergePayload($extra);
    }

    protected function getUri(Rocket $rocket): string
    {
        return 'v3/profitsharing/orders';
    }

    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    protected function getWechatExtra(array $params, Collection $payload): array
    {
        $config = get_wechat_config($params);

        $extra = [
            'appid' => $config['mp_app_id'] ?? null,
        ];

        if (Pay::MODE_SERVICE === ($config['mode'] ?? null)) {
            $extra['sub_mchid'] = $payload->get('sub_mchid', $config['sub_mch_id'] ?? '');
        }

        return $extra;
    }

    /**
     * @throws ContainerException
     * @throws InvalidParamsException
     * @throws ServiceNotFoundException
     */
    protected function getReceivers(array $params): array
    {
        $publicKey = $this->getPublicKey($params, $params['_serial_no'] ?? '');
        $receivers = $params['receivers'] ?? [];

        foreach ($receivers as $key => $receiver) {
            if (!empty($receiver['name'])) {
                $receivers[$key]['name'] = encrypt_wechat_contents($receiver['name'], $publicKey);
            }
        }

        return $receivers;
    }
}
