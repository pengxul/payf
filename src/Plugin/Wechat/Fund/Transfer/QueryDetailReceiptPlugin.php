<?php

declare(strict_types=1);

namespace Pengxul\Payf\Plugin\Wechat\Fund\Transfer;

use Pengxul\Payf\Exception\Exception;
use Pengxul\Payf\Exception\InvalidParamsException;
use Pengxul\Payf\Plugin\Wechat\GeneralPlugin;
use Pengxul\Payf\Rocket;

/**
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter4_3_10.shtml
 */
class QueryDetailReceiptPlugin extends GeneralPlugin
{
    protected function getMethod(): string
    {
        return 'GET';
    }

    /**
     * @throws InvalidParamsException
     */
    protected function doSomething(Rocket $rocket): void
    {
        $payload = $rocket->getPayload();

        if (!$payload->has('out_detail_no') || !$payload->has('accept_type')) {
            throw new InvalidParamsException(Exception::MISSING_NECESSARY_PARAMS);
        }

        $rocket->setPayload(null);
    }

    protected function getUri(Rocket $rocket): string
    {
        return 'v3/transfer-detail/electronic-receipts?'.$rocket->getPayload()->query();
    }
}
