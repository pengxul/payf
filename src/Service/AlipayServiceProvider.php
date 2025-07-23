<?php

declare(strict_types=1);

namespace Pengxul\Payf\Service;

use Pengxul\Payf\Contract\ServiceProviderInterface;
use Pengxul\Payf\Exception\ContainerException;
use Pengxul\Payf\Pay;
use Pengxul\Payf\Provider\Alipay;

class AlipayServiceProvider implements ServiceProviderInterface
{
    /**
     * @param mixed $data
     *
     * @throws ContainerException
     */
    public function register($data = null): void
    {
        $service = new Alipay();

        Pay::set(Alipay::class, $service);
        Pay::set('alipay', $service);
    }
}
