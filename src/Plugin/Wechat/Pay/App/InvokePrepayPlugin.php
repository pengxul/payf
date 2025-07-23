<?php

declare(strict_types=1);

namespace Pengxul\Payf\Plugin\Wechat\Pay\App;

use Exception;
use Pengxul\Payf\Exception\ContainerException;
use Pengxul\Payf\Exception\InvalidConfigException;
use Pengxul\Payf\Exception\ServiceNotFoundException;
use Pengxul\Payf\Rocket;
use Yansongda\Supports\Collection;
use Yansongda\Supports\Config;
use Yansongda\Supports\Str;

use function Pengxul\Payf\get_wechat_config;
use function Pengxul\Payf\get_wechat_sign;

/**
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter3_2_4.shtml
 */
class InvokePrepayPlugin extends \Pengxul\Payf\Plugin\Wechat\Pay\Common\InvokePrepayPlugin
{
    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     * @throws Exception
     */
    protected function getInvokeConfig(Rocket $rocket, string $prepayId): Config
    {
        $config = new Config([
            'appid' => $this->getAppId($rocket),
            'partnerid' => get_wechat_config($rocket->getParams())['mch_id'] ?? null,
            'prepayid' => $prepayId,
            'package' => 'Sign=WXPay',
            'noncestr' => Str::random(32),
            'timestamp' => time().'',
        ]);

        $config->set('sign', $this->getSign($config, $rocket->getParams()));

        return $config;
    }

    /**
     * @throws ContainerException
     * @throws InvalidConfigException
     * @throws ServiceNotFoundException
     */
    protected function getSign(Collection $invokeConfig, array $params): string
    {
        $contents = $invokeConfig->get('appid', '')."\n".
            $invokeConfig->get('timestamp', '')."\n".
            $invokeConfig->get('noncestr', '')."\n".
            $invokeConfig->get('prepayid', '')."\n";

        return get_wechat_sign($params, $contents);
    }

    protected function getConfigKey(): string
    {
        return 'app_id';
    }
}
