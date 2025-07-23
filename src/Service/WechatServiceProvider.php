<?php

declare(strict_types=1);

namespace Pengxul\Payf\Service;

use Pengxul\Payf\Contract\ServiceProviderInterface;
use Pengxul\Payf\Exception\ContainerException;
use Pengxul\Payf\Pay;
use Pengxul\Payf\Provider\Wechat;

class WechatServiceProvider implements ServiceProviderInterface
{
    /**
     * @param mixed $data
     *
     * @throws ContainerException
     */
    public function register($data = null): void
    {
        $service = new Wechat();

        Pay::set(Wechat::class, $service);
        Pay::set('wechat', $service);
    }
}
