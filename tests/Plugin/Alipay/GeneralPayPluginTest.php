<?php

namespace Pengxul\Payf\Tests\Plugin\Alipay;

use Pengxul\Payf\Rocket;
use Pengxul\Payf\Tests\Stubs\Plugin\AlipayGeneralPluginStub;
use Pengxul\Payf\Tests\TestCase;

class GeneralPayPluginTest extends TestCase
{
    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams([]);

        $plugin = new AlipayGeneralPluginStub();

        $result = $plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertStringContainsString('yansongda', $result->getPayload()->toJson());
    }
}

