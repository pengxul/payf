<?php

namespace Pengxul\Payf\Tests\Stubs\Traits;

use Pengxul\Payf\Rocket;
use Pengxul\Payf\Traits\SupportServiceProviderTrait;

class SupportServiceProviderPluginStub
{
    use SupportServiceProviderTrait;

    public function assembly(Rocket $rocket)
    {
        $this->loadAlipayServiceProvider($rocket);
    }
}
