<?php

namespace Pengxul\Payf\Tests\Plugin\Wechat\Fund\Profitsharing;

use GuzzleHttp\Psr7\Uri;
use Pengxul\Payf\Pay;
use Pengxul\Payf\Plugin\Wechat\Fund\Profitsharing\UnfreezePlugin;
use Pengxul\Payf\Provider\Wechat;
use Pengxul\Payf\Rocket;
use Pengxul\Payf\Tests\TestCase;
use Yansongda\Supports\Collection;

class UnfreezePluginTest extends TestCase
{
    /**
     * @var \Pengxul\Payf\Plugin\Wechat\Fund\Profitsharing\UnfreezePlugin
     */
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new UnfreezePlugin();
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams([])->setPayload(new Collection());

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $radar = $result->getRadar();
        $payload = $result->getPayload();

        self::assertEquals(new Uri(Wechat::URL[Pay::MODE_NORMAL].'v3/profitsharing/orders/unfreeze'), $radar->getUri());
        self::assertEquals('POST', $radar->getMethod());
        self::assertArrayNotHasKey('sub_mchid', $payload->all());
    }

    public function testPartner()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_config' => 'service_provider'])->setPayload(new Collection());

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $radar = $result->getRadar();
        $payload = $result->getPayload();

        self::assertEquals(new Uri(Wechat::URL[Pay::MODE_SERVICE].'v3/profitsharing/orders/unfreeze'), $radar->getUri());
        self::assertEquals('POST', $radar->getMethod());
        self::assertEquals('1600314070', $payload->get('sub_mchid'));
    }

    public function testPartnerDirectPayload()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_config' => 'service_provider'])->setPayload(new Collection(['sub_mchid' => '123']));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $payload = $result->getPayload();

        self::assertEquals('123', $payload->get('sub_mchid'));
    }
}
