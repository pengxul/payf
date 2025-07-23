<?php

declare(strict_types=1);

namespace Pengxul\Payf\Plugin\Alipay\User;

use Closure;
use Pengxul\Payf\Contract\PluginInterface;
use Pengxul\Payf\Logger;
use Pengxul\Payf\Rocket;

/**
 * @see https://opendocs.alipay.com/open/02aild
 */
class InfoSharePlugin implements PluginInterface
{
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[alipay][InfoSharePlugin] 插件开始装载', ['rocket' => $rocket]);

        $rocket->mergePayload([
            'method' => 'alipay.user.info.share',
            'auth_token' => $rocket->getParams()['auth_token'] ?? '',
        ]);

        Logger::info('[alipay][InfoSharePlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }
}
