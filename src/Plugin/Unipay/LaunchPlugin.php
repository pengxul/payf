<?php

declare(strict_types=1);

namespace Pengxul\Payf\Plugin\Unipay;

use Closure;
use Pengxul\Payf\Contract\PluginInterface;
use Pengxul\Payf\Exception\ContainerException;
use Pengxul\Payf\Exception\InvalidConfigException;
use Pengxul\Payf\Exception\InvalidResponseException;
use Pengxul\Payf\Exception\ServiceNotFoundException;
use Pengxul\Payf\Logger;
use Pengxul\Payf\Rocket;
use Yansongda\Supports\Collection;

use function Pengxul\Payf\should_do_http_request;
use function Pengxul\Payf\verify_unipay_sign;

class LaunchPlugin implements PluginInterface
{
    /**
     * @throws ContainerException
     * @throws InvalidConfigException
     * @throws InvalidResponseException
     * @throws ServiceNotFoundException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        /* @var Rocket $rocket */
        $rocket = $next($rocket);

        Logger::debug('[unipay][LaunchPlugin] 插件开始装载', ['rocket' => $rocket]);

        if (should_do_http_request($rocket->getDirection())) {
            $response = Collection::wrap($rocket->getDestination());
            $signature = $response->get('signature');
            $response->forget('signature');

            verify_unipay_sign(
                $rocket->getParams(),
                $response->sortKeys()->toString(),
                $signature
            );

            $rocket->setDestination($response);
        }

        Logger::info('[unipay][LaunchPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $rocket;
    }
}
