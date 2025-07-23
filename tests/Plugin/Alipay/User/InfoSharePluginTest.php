<?php

namespace Pengxul\Payf\Tests\Plugin\Alipay\User;

use Pengxul\Payf\Contract\DirectionInterface;
use Pengxul\Payf\Plugin\Alipay\User\InfoSharePlugin;
use Pengxul\Payf\Rocket;
use Pengxul\Payf\Tests\TestCase;

class InfoSharePluginTest extends TestCase
{
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new InfoSharePlugin();
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams([]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals(DirectionInterface::class, $result->getDirection());
        self::assertStringContainsString('alipay.user.info.share', $result->getPayload()->toJson());
        self::assertStringContainsString('auth_token', $result->getPayload()->toJson());
    }
}
