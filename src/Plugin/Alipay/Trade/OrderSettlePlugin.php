<?php

declare(strict_types=1);

namespace Pengxul\Payf\Plugin\Alipay\Trade;

use Pengxul\Payf\Plugin\Alipay\GeneralPlugin;

/**
 * @see https://opendocs.alipay.com/open/028xqz
 */
class OrderSettlePlugin extends GeneralPlugin
{
    protected function getMethod(): string
    {
        return 'alipay.trade.order.settle';
    }
}
