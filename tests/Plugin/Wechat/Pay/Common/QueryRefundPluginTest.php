<?php

namespace Pengxul\Payf\Tests\Plugin\Wechat\Pay\Common;

use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\RequestInterface;
use Pengxul\Payf\Pay;
use Pengxul\Payf\Plugin\Wechat\Pay\Common\QueryRefundPlugin;
use Pengxul\Payf\Provider\Wechat;
use Pengxul\Payf\Rocket;
use Pengxul\Payf\Tests\TestCase;
use Yansongda\Supports\Collection;

class QueryRefundPluginTest extends TestCase
{
    /**
     * @var \Pengxul\Payf\Plugin\Wechat\Pay\Common\QueryRefundPlugin
     */
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new QueryRefundPlugin();
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams([])->setPayload(new Collection(['out_refund_no' => '123']));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $radar = $result->getRadar();
        $payload = $result->getPayload();

        self::assertInstanceOf(RequestInterface::class, $radar);
        self::assertEquals('GET', $radar->getMethod());
        self::assertEquals(new Uri(Wechat::URL[Pay::MODE_NORMAL].'v3/refund/domestic/refunds/123'), $radar->getUri());
        self::assertNull($payload);
    }

    public function testPartner()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_config' => 'service_provider'])->setPayload(new Collection(['out_refund_no' => '123']));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $radar = $result->getRadar();
        $payload = $result->getPayload();

        self::assertInstanceOf(RequestInterface::class, $radar);
        self::assertEquals('GET', $radar->getMethod());
        self::assertEquals(new Uri(Wechat::URL[Pay::MODE_SERVICE].'v3/refund/domestic/refunds/123?sub_mchid=1600314070'), $radar->getUri());
        self::assertNull($payload);
    }

    public function testPartnerDirectPayload()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_config' => 'service_provider'])->setPayload(new Collection(['sub_mchid' => '123','out_refund_no' => '456']));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $radar = $result->getRadar();

        self::assertEquals(new Uri(Wechat::URL[Pay::MODE_SERVICE].'v3/refund/domestic/refunds/456?sub_mchid=123'), $radar->getUri());
    }
}
