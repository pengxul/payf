<?php

namespace Pengxul\Payf\Tests\Plugin\Alipay\Trade;

use Pengxul\Payf\Direction\ResponseDirection;
use Pengxul\Payf\Plugin\Alipay\Trade\AppPayPlugin;
use Pengxul\Payf\Rocket;
use Pengxul\Payf\Tests\TestCase;

class AppPayPluginTest extends TestCase
{
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new AppPayPlugin();
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams([]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals(ResponseDirection::class, $result->getDirection());
        self::assertStringContainsString('alipay.trade.app.pay', $result->getPayload()->toJson());
        self::assertStringContainsString('QUICK_MSECURITY_PAY', $result->getPayload()->toJson());
    }
}
