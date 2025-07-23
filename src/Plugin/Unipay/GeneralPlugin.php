<?php

declare(strict_types=1);

namespace Pengxul\Payf\Plugin\Unipay;

use Closure;
use Psr\Http\Message\RequestInterface;
use Pengxul\Payf\Contract\PluginInterface;
use Pengxul\Payf\Exception\ContainerException;
use Pengxul\Payf\Exception\ServiceNotFoundException;
use Pengxul\Payf\Logger;
use Pengxul\Payf\Pay;
use Pengxul\Payf\Provider\Unipay;
use Pengxul\Payf\Request;
use Pengxul\Payf\Rocket;

use function Pengxul\Payf\get_unipay_config;

abstract class GeneralPlugin implements PluginInterface
{
    /**
     * @throws ServiceNotFoundException
     * @throws ContainerException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[unipay][GeneralPlugin] 通用插件开始装载', ['rocket' => $rocket]);

        $rocket->setRadar($this->getRequest($rocket));
        $this->doSomething($rocket);

        Logger::info('[unipay][GeneralPlugin] 通用插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }

    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    protected function getRequest(Rocket $rocket): RequestInterface
    {
        return new Request(
            $this->getMethod(),
            $this->getUrl($rocket),
            $this->getHeaders(),
        );
    }

    protected function getMethod(): string
    {
        return 'POST';
    }

    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    protected function getUrl(Rocket $rocket): string
    {
        $url = $this->getUri($rocket);

        if (0 === strpos($url, 'http')) {
            return $url;
        }

        $config = get_unipay_config($rocket->getParams());

        return Unipay::URL[$config['mode'] ?? Pay::MODE_NORMAL].$url;
    }

    protected function getHeaders(): array
    {
        return [
            'User-Agent' => 'yansongda/pay-v3',
            'Content-Type' => 'application/x-www-form-urlencoded;charset=utf-8',
        ];
    }

    abstract protected function doSomething(Rocket $rocket): void;

    abstract protected function getUri(Rocket $rocket): string;
}
