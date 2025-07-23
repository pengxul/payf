<?php

declare(strict_types=1);

namespace Pengxul\Payf\Tests\Stubs;

use Pengxul\Payf\Contract\ServiceProviderInterface;
use Pengxul\Payf\Pay;

class FooServiceProviderStub implements ServiceProviderInterface
{
    /**
     * @throws \Pengxul\Payf\Exception\ContainerException
     */
    public function register($data = null): void
    {
        Pay::set('foo', 'bar');
    }
}
