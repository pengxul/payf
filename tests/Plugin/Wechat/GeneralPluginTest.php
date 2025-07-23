<?php

namespace Pengxul\Payf\Tests\Plugin\Wechat;

use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\RequestInterface;
use Pengxul\Payf\Pay;
use Pengxul\Payf\Provider\Wechat;
use Pengxul\Payf\Rocket;
use Pengxul\Payf\Tests\Stubs\Plugin\WechatGeneralPluginStub;
use Pengxul\Payf\Tests\TestCase;

class GeneralPluginTest extends TestCase
{
    protected WechatGeneralPluginStub $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new WechatGeneralPluginStub();
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams([]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $radar = $result->getRadar();

        self::assertInstanceOf(RequestInterface::class, $radar);
        self::assertEquals('POST', $radar->getMethod());
        self::assertEquals(new Uri(Wechat::URL[Pay::MODE_NORMAL].'yansongda/pay'), $radar->getUri());
        self::assertEquals('mp_app_id', $result->getPayload()['config_key']);
    }

    public function testPartner()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_config' => 'service_provider']);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $radar = $result->getRadar();

        self::assertInstanceOf(RequestInterface::class, $radar);
        self::assertEquals('POST', $radar->getMethod());
        self::assertEquals(new Uri(Wechat::URL[Pay::MODE_SERVICE].'yansongda/pay/partner'), $radar->getUri());
    }

    public function testGetConfigKey()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_type' => 'mini']);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $radar = $result->getRadar();

        self::assertInstanceOf(RequestInterface::class, $radar);
        self::assertEquals('POST', $radar->getMethod());
        self::assertEquals(new Uri(Wechat::URL[Pay::MODE_NORMAL].'yansongda/pay'), $radar->getUri());
        self::assertEquals('mini_app_id', $result->getPayload()['config_key']);
    }
}
