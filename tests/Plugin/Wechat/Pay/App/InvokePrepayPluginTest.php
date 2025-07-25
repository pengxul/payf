<?php

namespace Pengxul\Payf\Tests\Plugin\Wechat\Pay\App;

use Pengxul\Payf\Plugin\Wechat\Pay\App\InvokePrepayPlugin;
use Pengxul\Payf\Rocket;
use Pengxul\Payf\Tests\TestCase;
use Yansongda\Supports\Collection;

class InvokePrepayPluginTest extends TestCase
{
    protected InvokePrepayPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new InvokePrepayPlugin();
    }

    public function testNormal()
    {
        $rocket = (new Rocket())->setDestination(new Collection(['prepay_id' => 'yansongda']));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $contents = $result->getDestination();

        self::assertArrayHasKey('appid', $contents->all());
        self::assertArrayHasKey('partnerid', $contents->all());
        self::assertArrayHasKey('package', $contents->all());
        self::assertEquals('Sign=WXPay', $contents->get('package'));
        self::assertArrayHasKey('sign', $contents->all());
        self::assertArrayHasKey('timestamp', $contents->all());
        self::assertArrayHasKey('noncestr', $contents->all());
        self::assertEquals('yansongda', $contents->get('appid'));
    }

    public function testPartner()
    {
        $rocket = (new Rocket())
            ->setParams(['_config' => 'service_provider4'])
            ->setPayload(new Collection(['sub_appid' => '123']))
            ->setDestination(new Collection(['prepay_id' => 'yansongda']));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $contents = $result->getDestination();

        self::assertArrayHasKey('appid', $contents->all());
        self::assertEquals('123', $contents->get('appid'));
    }
}
