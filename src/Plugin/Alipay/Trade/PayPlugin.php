<?php

declare(strict_types=1);

namespace Pengxul\Payf\Plugin\Alipay\Trade;

use Closure;
use Pengxul\Payf\Contract\PluginInterface;
use Pengxul\Payf\Exception\ContainerException;
use Pengxul\Payf\Exception\ServiceNotFoundException;
use Pengxul\Payf\Logger;
use Pengxul\Payf\Rocket;
use Pengxul\Payf\Traits\SupportServiceProviderTrait;

/**
 * @see https://opendocs.alipay.com/open/02fkat?ref=api&scene=common
 */
class PayPlugin implements PluginInterface
{
    use SupportServiceProviderTrait;

    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[alipay][PayPlugin] 插件开始装载', ['rocket' => $rocket]);

        $this->loadAlipayServiceProvider($rocket);

        $rocket->mergePayload([
            'method' => 'alipay.trade.pay',
            'biz_content' => array_merge(
                [
                    'product_code' => 'FACE_TO_FACE_PAYMENT',
                    'scene' => 'bar_code',
                ],
                $rocket->getParams(),
            ),
        ]);

        Logger::info('[alipay][PayPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }
}
