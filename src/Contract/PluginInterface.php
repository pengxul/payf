<?php

declare(strict_types=1);

namespace Pengxul\Payf\Contract;

use Closure;
use Pengxul\Payf\Rocket;

interface PluginInterface
{
    public function assembly(Rocket $rocket, Closure $next): Rocket;
}
