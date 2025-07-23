<?php

declare(strict_types=1);

namespace Pengxul\Payf\Plugin\Alipay\Trade;

use Pengxul\Payf\Plugin\Alipay\GeneralPlugin;

class OrderPlugin extends GeneralPlugin
{
    protected function getMethod(): string
    {
        return 'alipay.trade.order.pay';
    }
}
