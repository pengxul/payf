<?php

declare(strict_types=1);

namespace Pengxul\Payf\Service;

use Pengxul\Payf\Contract\ConfigInterface;
use Pengxul\Payf\Contract\LoggerInterface;
use Pengxul\Payf\Contract\ServiceProviderInterface;
use Pengxul\Payf\Exception\ContainerException;
use Pengxul\Payf\Exception\ServiceNotFoundException;
use Pengxul\Payf\Pay;
use Yansongda\Supports\Logger;

class LoggerServiceProvider implements ServiceProviderInterface
{
    /**
     * @param mixed $data
     *
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    public function register($data = null): void
    {
        /* @var ConfigInterface $config */
        $config = Pay::get(ConfigInterface::class);

        if (class_exists(\Monolog\Logger::class) && true === $config->get('logger.enable', false)) {
            $logger = new Logger(array_merge(
                ['identify' => 'yansongda.pay'],
                $config->get('logger', [])
            ));

            Pay::set(LoggerInterface::class, $logger);
        }
    }
}
