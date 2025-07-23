<?php

declare(strict_types=1);

namespace Pengxul\Payf\Plugin\Wechat\Pay\Combine;

use Pengxul\Payf\Exception\Exception;
use Pengxul\Payf\Exception\InvalidParamsException;
use Pengxul\Payf\Rocket;

/**
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter5_1_11.shtml
 */
class QueryPlugin extends \Pengxul\Payf\Plugin\Wechat\Pay\Common\QueryPlugin
{
    protected function getUri(Rocket $rocket): string
    {
        $payload = $rocket->getPayload();

        if (!$payload->has('combine_out_trade_no') && !$payload->has('transaction_id')) {
            throw new InvalidParamsException(Exception::MISSING_NECESSARY_PARAMS);
        }

        return 'v3/combine-transactions/out-trade-no/'.
            $payload->get('combine_out_trade_no', $payload->get('transaction_id'));
    }
}
