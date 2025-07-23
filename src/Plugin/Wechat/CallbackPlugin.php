<?php

declare(strict_types=1);

namespace Pengxul\Payf\Plugin\Wechat;

use Closure;
use Psr\Http\Message\ServerRequestInterface;
use Pengxul\Payf\Contract\PluginInterface;
use Pengxul\Payf\Direction\NoHttpRequestDirection;
use Pengxul\Payf\Exception\ContainerException;
use Pengxul\Payf\Exception\Exception;
use Pengxul\Payf\Exception\InvalidConfigException;
use Pengxul\Payf\Exception\InvalidParamsException;
use Pengxul\Payf\Exception\InvalidResponseException;
use Pengxul\Payf\Exception\ServiceNotFoundException;
use Pengxul\Payf\Logger;
use Pengxul\Payf\Rocket;
use Yansongda\Supports\Collection;

use function Pengxul\Payf\decrypt_wechat_resource;
use function Pengxul\Payf\verify_wechat_sign;

class CallbackPlugin implements PluginInterface
{
    /**
     * @throws ContainerException
     * @throws InvalidConfigException
     * @throws ServiceNotFoundException
     * @throws InvalidResponseException
     * @throws InvalidParamsException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[wechat][CallbackPlugin] 插件开始装载', ['rocket' => $rocket]);

        $this->formatRequestAndParams($rocket);

        /* @phpstan-ignore-next-line */
        verify_wechat_sign($rocket->getDestinationOrigin(), $rocket->getParams());

        $body = json_decode((string) $rocket->getDestination()->getBody(), true);

        $rocket->setDirection(NoHttpRequestDirection::class)->setPayload(new Collection($body));

        $body['resource'] = decrypt_wechat_resource($body['resource'] ?? [], $rocket->getParams());

        $rocket->setDestination(new Collection($body));

        Logger::info('[wechat][CallbackPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }

    /**
     * @throws InvalidParamsException
     */
    protected function formatRequestAndParams(Rocket $rocket): void
    {
        $request = $rocket->getParams()['request'] ?? null;

        if (!$request instanceof ServerRequestInterface) {
            throw new InvalidParamsException(Exception::REQUEST_NULL_ERROR);
        }

        $rocket->setDestination(clone $request)
            ->setDestinationOrigin($request)
            ->setParams($rocket->getParams()['params'] ?? [])
        ;
    }
}
