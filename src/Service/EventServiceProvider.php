<?php

declare(strict_types=1);

namespace Pengxul\Payf\Service;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Pengxul\Payf\Contract\EventDispatcherInterface;
use Pengxul\Payf\Contract\ServiceProviderInterface;
use Pengxul\Payf\Exception\ContainerException;
use Pengxul\Payf\Pay;

class EventServiceProvider implements ServiceProviderInterface
{
    /**
     * @param mixed $data
     *
     * @throws ContainerException
     */
    public function register($data = null): void
    {
        if (class_exists(EventDispatcher::class)) {
            Pay::set(EventDispatcherInterface::class, new EventDispatcher());
        }
    }
}
