<?php

declare(strict_types=1);

namespace Pengxul\Payf\Service;

use Closure;
use Hyperf\Pimple\ContainerFactory as DefaultContainer;
use Hyperf\Utils\ApplicationContext as HyperfContainer;
use Illuminate\Container\Container as LaravelContainer;
use Psr\Container\ContainerInterface;
use Pengxul\Payf\Contract\ServiceProviderInterface;
use Pengxul\Payf\Exception\ContainerException;
use Pengxul\Payf\Exception\ContainerNotFoundException;
use Pengxul\Payf\Exception\Exception;
use Pengxul\Payf\Pay;

/**
 * @codeCoverageIgnore
 */
class ContainerServiceProvider implements ServiceProviderInterface
{
    private array $detectApplication = [
        'laravel' => LaravelContainer::class,
        'hyperf' => HyperfContainer::class,
    ];

    /**
     * @param mixed $data
     *
     * @throws ContainerException
     */
    public function register($data = null): void
    {
        if ($data instanceof ContainerInterface || $data instanceof Closure) {
            Pay::setContainer($data);

            return;
        }

        if (Pay::hasContainer()) {
            return;
        }

        foreach ($this->detectApplication as $framework => $application) {
            $method = $framework.'Application';

            if (class_exists($application) && method_exists($this, $method) && $this->{$method}()) {
                return;
            }
        }

        $this->defaultApplication();
    }

    /**
     * @throws ContainerException
     * @throws ContainerNotFoundException
     */
    protected function laravelApplication(): bool
    {
        Pay::setContainer(static fn () => LaravelContainer::getInstance());

        Pay::set(\Pengxul\Payf\Contract\ContainerInterface::class, LaravelContainer::getInstance());

        if (!Pay::has(ContainerInterface::class)) {
            Pay::set(ContainerInterface::class, LaravelContainer::getInstance());
        }

        return true;
    }

    /**
     * @throws ContainerException
     * @throws ContainerNotFoundException
     */
    protected function hyperfApplication(): bool
    {
        if (!HyperfContainer::hasContainer()) {
            return false;
        }

        Pay::setContainer(static fn () => HyperfContainer::getContainer());

        Pay::set(\Pengxul\Payf\Contract\ContainerInterface::class, HyperfContainer::getContainer());

        if (!Pay::has(ContainerInterface::class)) {
            Pay::set(ContainerInterface::class, HyperfContainer::getContainer());
        }

        return true;
    }

    /**
     * @throws ContainerNotFoundException
     */
    protected function defaultApplication(): void
    {
        if (!class_exists(DefaultContainer::class)) {
            throw new ContainerNotFoundException('Init failed! Maybe you should install `hyperf/pimple` first', Exception::CONTAINER_NOT_FOUND);
        }

        $container = (new DefaultContainer())();

        Pay::setContainer($container);
    }
}
