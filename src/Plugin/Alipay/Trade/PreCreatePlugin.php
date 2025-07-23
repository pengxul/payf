<?php

declare(strict_types=1);

namespace Pengxul\Payf\Plugin\Alipay\Trade;

use Pengxul\Payf\Exception\ContainerException;
use Pengxul\Payf\Exception\ServiceNotFoundException;
use Pengxul\Payf\Plugin\Alipay\GeneralPlugin;
use Pengxul\Payf\Rocket;
use Pengxul\Payf\Traits\SupportServiceProviderTrait;

/**
 * @see https://opendocs.alipay.com/open/02ekfg?scene=common
 */
class PreCreatePlugin extends GeneralPlugin
{
    use SupportServiceProviderTrait;

    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    protected function doSomethingBefore(Rocket $rocket): void
    {
        $this->loadAlipayServiceProvider($rocket);
    }

    protected function getMethod(): string
    {
        return 'alipay.trade.precreate';
    }
}
