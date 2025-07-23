<?php

declare(strict_types=1);

namespace Pengxul\Payf\Plugin\Wechat;

use Closure;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Pengxul\Payf\Contract\PluginInterface;
use Pengxul\Payf\Exception\ContainerException;
use Pengxul\Payf\Exception\Exception;
use Pengxul\Payf\Exception\InvalidConfigException;
use Pengxul\Payf\Exception\InvalidParamsException;
use Pengxul\Payf\Exception\InvalidResponseException;
use Pengxul\Payf\Exception\ServiceNotFoundException;
use Pengxul\Payf\Logger;
use Pengxul\Payf\Rocket;
use Yansongda\Supports\Collection;

use function Pengxul\Payf\should_do_http_request;
use function Pengxul\Payf\verify_wechat_sign;

class LaunchPlugin implements PluginInterface
{
    /**
     * @throws ContainerException
     * @throws InvalidConfigException
     * @throws InvalidResponseException
     * @throws ServiceNotFoundException
     * @throws InvalidParamsException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        /* @var Rocket $rocket */
        $rocket = $next($rocket);

        Logger::debug('[wechat][LaunchPlugin] 插件开始装载', ['rocket' => $rocket]);

        if (should_do_http_request($rocket->getDirection())) {
            verify_wechat_sign($rocket->getDestinationOrigin(), $rocket->getParams());

            $rocket->setDestination($this->validateResponse($rocket));
        }

        Logger::info('[wechat][LaunchPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $rocket;
    }

    /**
     * @return null|array|Collection|MessageInterface
     *
     * @throws InvalidResponseException
     */
    protected function validateResponse(Rocket $rocket)
    {
        $response = $rocket->getDestination();

        if ($response instanceof ResponseInterface
            && ($response->getStatusCode() < 200 || $response->getStatusCode() >= 300)) {
            throw new InvalidResponseException(Exception::INVALID_RESPONSE_CODE);
        }

        return $response;
    }
}
