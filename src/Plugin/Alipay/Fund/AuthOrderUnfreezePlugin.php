<?php

declare(strict_types=1);

namespace Pengxul\Payf\Plugin\Alipay\Fund;

use Pengxul\Payf\Plugin\Alipay\GeneralPlugin;

/**
 * @see https://opendocs.alipay.com/open/02fkbc
 */
class AuthOrderUnfreezePlugin extends GeneralPlugin
{
    protected function getMethod(): string
    {
        return 'alipay.fund.auth.order.unfreeze';
    }
}
