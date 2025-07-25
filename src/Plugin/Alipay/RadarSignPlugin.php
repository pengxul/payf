<?php

declare(strict_types=1);

namespace Pengxul\Payf\Plugin\Alipay;

use Closure;
use Pengxul\Payf\Contract\PluginInterface;
use Pengxul\Payf\Exception\ContainerException;
use Pengxul\Payf\Exception\Exception;
use Pengxul\Payf\Exception\InvalidConfigException;
use Pengxul\Payf\Exception\ServiceNotFoundException;
use Pengxul\Payf\Logger;
use Pengxul\Payf\Pay;
use Pengxul\Payf\Provider\Alipay;
use Pengxul\Payf\Request;
use Pengxul\Payf\Rocket;
use Yansongda\Supports\Collection;
use Yansongda\Supports\Str;

use function Pengxul\Payf\get_alipay_config;
use function Pengxul\Payf\get_private_cert;

class RadarSignPlugin implements PluginInterface
{
    /**
     * @throws ContainerException
     * @throws InvalidConfigException
     * @throws ServiceNotFoundException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[alipay][RadarSignPlugin] 插件开始装载', ['rocket' => $rocket]);

        $this->sign($rocket);

        $this->reRadar($rocket);

        Logger::info('[alipay][RadarSignPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }

    /**
     * @throws ContainerException
     * @throws InvalidConfigException
     * @throws ServiceNotFoundException
     */
    protected function sign(Rocket $rocket): void
    {
        $this->formatPayload($rocket);

        $sign = $this->getSign($rocket);

        $rocket->mergePayload(['sign' => $sign]);
    }

    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    protected function reRadar(Rocket $rocket): void
    {
        $params = $rocket->getParams();

        $rocket->setRadar(new Request(
            $this->getMethod($params),
            $this->getUrl($params),
            $this->getHeaders(),
            $this->getBody($rocket->getPayload()),
        ));
    }

    protected function formatPayload(Rocket $rocket): void
    {
        $payload = $rocket->getPayload()->filter(fn ($v, $k) => '' !== $v && !is_null($v) && 'sign' != $k);

        $contents = array_filter($payload->get('biz_content', []), fn ($v, $k) => !Str::startsWith(strval($k), '_'), ARRAY_FILTER_USE_BOTH);

        $rocket->setPayload(
            $payload->merge(['biz_content' => json_encode($contents)])
        );
    }

    /**
     * @throws ContainerException
     * @throws InvalidConfigException
     * @throws ServiceNotFoundException
     */
    protected function getSign(Rocket $rocket): string
    {
        $privateKey = $this->getPrivateKey($rocket->getParams());

        $content = $rocket->getPayload()->sortKeys()->toString();

        openssl_sign($content, $sign, $privateKey, OPENSSL_ALGO_SHA256);

        return base64_encode($sign);
    }

    /**
     * @throws ContainerException
     * @throws InvalidConfigException
     * @throws ServiceNotFoundException
     */
    protected function getPrivateKey(array $params): string
    {
        $privateKey = get_alipay_config($params)['app_secret_cert'] ?? null;

        if (is_null($privateKey)) {
            throw new InvalidConfigException(Exception::ALIPAY_CONFIG_ERROR, 'Missing Alipay Config -- [app_secret_cert]');
        }

        return get_private_cert($privateKey);
    }

    protected function getMethod(array $params): string
    {
        return strtoupper($params['_method'] ?? 'POST');
    }

    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    protected function getUrl(array $params): string
    {
        $config = get_alipay_config($params);

        return Alipay::URL[$config['mode'] ?? Pay::MODE_NORMAL];
    }

    protected function getHeaders(): array
    {
        return [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];
    }

    protected function getBody(Collection $payload): string
    {
        return $payload->query();
    }
}
