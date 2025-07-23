<?php

declare(strict_types=1);

namespace Pengxul\Payf\Plugin\Alipay\User;

use Closure;
use Pengxul\Payf\Contract\PluginInterface;
use Pengxul\Payf\Direction\ResponseDirection;
use Pengxul\Payf\Logger;
use Pengxul\Payf\Rocket;

/**
 * @see https://opendocs.alipay.com/open/02fkan?ref=api&scene=35
 */
class AgreementPageSignPlugin implements PluginInterface
{
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[alipay][AgreementPageSignPlugin] 插件开始装载', ['rocket' => $rocket]);

        $rocket->setDirection(ResponseDirection::class)
            ->mergePayload([
                'method' => 'alipay.user.agreement.page.sign',
                'biz_content' => array_merge(
                    ['product_code' => 'CYCLE_PAY_AUTH'],
                    $rocket->getParams()
                ),
            ])
        ;

        Logger::info('[alipay][AgreementPageSignPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }
}
