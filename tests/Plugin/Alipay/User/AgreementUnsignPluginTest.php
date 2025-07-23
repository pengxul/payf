<?php

namespace Pengxul\Payf\Tests\Plugin\Alipay\User;

use Pengxul\Payf\Contract\DirectionInterface;
use Pengxul\Payf\Plugin\Alipay\User\AgreementUnsignPlugin;
use Pengxul\Payf\Rocket;
use Pengxul\Payf\Tests\TestCase;

class AgreementUnsignPluginTest extends TestCase
{
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new AgreementUnsignPlugin();
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams([]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals(DirectionInterface::class, $result->getDirection());
        self::assertStringContainsString('alipay.user.agreement.unsign', $result->getPayload()->toJson());
        self::assertStringContainsString('CYCLE_PAY_AUTH_P', $result->getPayload()->toJson());
    }
}
