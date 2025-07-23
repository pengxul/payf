<?php

declare(strict_types=1);

namespace Pengxul\Payf\Plugin\Alipay\Fund;

use Pengxul\Payf\Plugin\Alipay\GeneralPlugin;

class TransTobankTransferPlugin extends GeneralPlugin
{
    protected function getMethod(): string
    {
        return 'alipay.fund.trans.tobank.transfer';
    }
}
