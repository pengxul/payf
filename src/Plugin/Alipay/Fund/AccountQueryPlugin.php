<?php

declare(strict_types=1);

namespace Pengxul\Payf\Plugin\Alipay\Fund;

use Closure;
use Pengxul\Payf\Contract\PluginInterface;
use Pengxul\Payf\Logger;
use Pengxul\Payf\Rocket;

/**
 * @see https://opendocs.alipay.com/open/02byuq?scene=common
 */
class AccountQueryPlugin implements PluginInterface
{
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[alipay][AccountQueryPlugin] 插件开始装载', ['rocket' => $rocket]);

        $rocket->mergePayload([
            'method' => 'alipay.fund.account.query',
            'biz_content' => array_merge(
                [
                    'product_code' => 'TRANS_ACCOUNT_NO_PWD',
                ],
                $rocket->getParams(),
            ),
        ]);

        Logger::info('[alipay][AccountQueryPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }
}
