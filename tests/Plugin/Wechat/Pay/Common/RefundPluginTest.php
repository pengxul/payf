<?php

namespace Pengxul\Payf\Tests\Plugin\Wechat\Pay\Common;

use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\RequestInterface;
use Pengxul\Payf\Pay;
use Pengxul\Payf\Plugin\Wechat\Pay\Common\RefundPlugin;
use Pengxul\Payf\Provider\Wechat;
use Pengxul\Payf\Rocket;
use Pengxul\Payf\Tests\TestCase;
use Yansongda\Supports\Collection;

class RefundPluginTest extends TestCase
{
    /**
     * @var \Pengxul\Payf\Plugin\Wechat\Pay\Common\RefundPlugin
     */
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new RefundPlugin();
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams([])->setPayload(new Collection());

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $radar = $result->getRadar();

        self::assertInstanceOf(RequestInterface::class, $radar);
        self::assertEquals('POST', $radar->getMethod());
        self::assertEquals(new Uri(Wechat::URL[Pay::MODE_NORMAL].'v3/refund/domestic/refunds'), $radar->getUri());
    }

    public function testPartner()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_config' => 'service_provider'])->setPayload(new Collection());

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $radar = $result->getRadar();
        $payload = $result->getPayload();

        self::assertInstanceOf(RequestInterface::class, $radar);
        self::assertEquals('POST', $radar->getMethod());
        self::assertEquals(new Uri(Wechat::URL[Pay::MODE_SERVICE].'v3/refund/domestic/refunds'), $radar->getUri());
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

    public function testNormalNotifyUrl()
    {
        $rocket = (new Rocket())
            ->setParams([])->setPayload(new Collection());
        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
        self::assertEquals('pay.yansongda.cn', $result->getPayload()->get('notify_url'));

        $rocket = (new Rocket())
            ->setParams([])->setPayload(new Collection(['notify_url' => 'yansongda.cn']));
        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
        self::assertEquals('yansongda.cn', $result->getPayload()->get('notify_url'));
    }

    public function testEmptyNotifyUrl()
    {
        $rocket = (new Rocket())
            ->setParams(['_config' => 'empty_wechat_public_cert'])->setPayload(new Collection());
        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertArrayNotHasKey('notify_url', $result->getPayload()->all());
    }
}
