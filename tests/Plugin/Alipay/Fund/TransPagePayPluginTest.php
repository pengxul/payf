<?php

namespace Pengxul\Payf\Tests\Plugin\Alipay\Fund;

use Pengxul\Payf\Direction\ResponseDirection;
use Pengxul\Payf\Plugin\Alipay\Fund\TransPagePayPlugin;
use Pengxul\Payf\Rocket;
use Pengxul\Payf\Tests\TestCase;

class TransPagePayPluginTest extends TestCase
{
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new TransPagePayPlugin();
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams([]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals(ResponseDirection::class, $result->getDirection());
        self::assertStringContainsString('alipay.fund.trans.page.pay', $result->getPayload()->toJson());
    }
}
