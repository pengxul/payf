<?php

namespace Pengxul\Payf\Tests\Plugin\Alipay\Fund;

use Pengxul\Payf\Direction\ResponseDirection;
use Pengxul\Payf\Plugin\Alipay\Fund\AuthOrderFreezePlugin;
use Pengxul\Payf\Rocket;
use Pengxul\Payf\Tests\TestCase;

class AuthOrderFreezePluginTest extends TestCase
{
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new AuthOrderFreezePlugin();
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams([]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertNotEquals(ResponseDirection::class, $result->getDirection());
        self::assertStringContainsString('alipay.fund.auth.order.freeze', $result->getPayload()->toJson());
        self::assertStringContainsString('PRE_AUTH', $result->getPayload()->toJson());
    }
}
