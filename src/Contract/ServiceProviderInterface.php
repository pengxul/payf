<?php

declare(strict_types=1);

namespace Pengxul\Payf\Contract;

use Pengxul\Payf\Exception\ContainerException;

interface ServiceProviderInterface
{
    /**
     * @param mixed $data
     *
     * @throws ContainerException
     */
    public function register($data = null): void;
}
