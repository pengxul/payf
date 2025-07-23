<?php

declare(strict_types=1);

namespace Pengxul\Payf\Plugin\Wechat\Ecommerce\Refund;

use Pengxul\Payf\Exception\ContainerException;
use Pengxul\Payf\Exception\Exception;
use Pengxul\Payf\Exception\InvalidParamsException;
use Pengxul\Payf\Exception\ServiceNotFoundException;
use Pengxul\Payf\Plugin\Wechat\GeneralPlugin;
use Pengxul\Payf\Rocket;
use Yansongda\Supports\Collection;

use function Pengxul\Payf\get_wechat_config;

/**
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3_partner/apis/chapter7_6_4.shtml
 */
class ReturnAdvancePlugin extends GeneralPlugin
{
    /**
     * @throws InvalidParamsException
     */
    protected function getUri(Rocket $rocket): string
    {
        throw new InvalidParamsException(Exception::NOT_IN_SERVICE_MODE);
    }

    /**
     * @throws InvalidParamsException
     */
    protected function getPartnerUri(Rocket $rocket): string
    {
        $payload = $rocket->getPayload();

        if (!$payload->has('refund_id')) {
            throw new InvalidParamsException(Exception::MISSING_NECESSARY_PARAMS);
        }

        return 'v3/ecommerce/refunds/'.$payload->get('refund_id').'/return-advance';
    }

    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    protected function doSomething(Rocket $rocket): void
    {
        $config = get_wechat_config($rocket->getParams());

        $rocket->setPayload(new Collection([
            'sub_mchid' => $rocket->getPayload()->get('sub_mchid', $config['sub_mch_id'] ?? ''),
        ]));
    }
}
