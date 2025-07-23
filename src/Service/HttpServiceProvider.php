<?php

declare(strict_types=1);

namespace Pengxul\Payf\Service;

use GuzzleHttp\Client;
use Pengxul\Payf\Contract\ConfigInterface;
use Pengxul\Payf\Contract\HttpClientInterface;
use Pengxul\Payf\Contract\ServiceProviderInterface;
use Pengxul\Payf\Exception\ContainerException;
use Pengxul\Payf\Exception\ServiceNotFoundException;
use Pengxul\Payf\Pay;
use Yansongda\Supports\Config;

class HttpServiceProvider implements ServiceProviderInterface
{
    /**
     * @param mixed $data
     *
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    public function register($data = null): void
    {
        /* @var Config $config */
        $config = Pay::get(ConfigInterface::class);

        if (class_exists(Client::class)) {
            $service = new Client($config->get('http', []));

            Pay::set(HttpClientInterface::class, $service);
        }
    }
}
