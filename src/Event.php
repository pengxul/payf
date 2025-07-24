<?php

declare(strict_types=1);

namespace Pengxul\Payf;

use Pengxul\Payf\Contract\EventDispatcherInterface;
use Pengxul\Payf\Exception\ContainerException;
use Pengxul\Payf\Exception\InvalidConfigException;
use Pengxul\Payf\Exception\ServiceNotFoundException;

/**
 * @method static Event\Event dispatch(object $event)
 */
class Event
{
    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     * @throws InvalidConfigException
     */
    public static function __callStatic(string $method, array $args): void
    {
        if (!Pay::hasContainer() || !Pay::has(EventDispatcherInterface::class)) {
            return;
        }

        $class = Pay::get(EventDispatcherInterface::class);

        if ($class instanceof \Psr\EventDispatcher\EventDispatcherInterface) {
            $class->{$method}(...$args);

            return;
        }

        throw new InvalidConfigException(Exception\Exception::EVENT_CONFIG_ERROR);
    }
}
