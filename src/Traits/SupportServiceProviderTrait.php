<?php

declare(strict_types=1);

namespace Pengxul\Payf\Traits;

use Pengxul\Payf\Exception\ContainerException;
use Pengxul\Payf\Exception\ServiceNotFoundException;
use Pengxul\Payf\Pay;
use Pengxul\Payf\Rocket;

use function Pengxul\Payf\get_alipay_config;

trait SupportServiceProviderTrait
{
    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    protected function loadAlipayServiceProvider(Rocket $rocket): void
    {
        $params = $rocket->getParams();
        $config = get_alipay_config($params);
        $serviceProviderId = $config['service_provider_id'] ?? null;

        if (Pay::MODE_SERVICE !== ($config['mode'] ?? Pay::MODE_NORMAL)
            || empty($serviceProviderId)) {
            return;
        }

        $rocket->mergeParams([
            'extend_params' => array_merge($params['extend_params'] ?? [], ['sys_service_provider_id' => $serviceProviderId]),
        ]);
    }
}
