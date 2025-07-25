<?php

namespace Pengxul\Payf\Tests\Plugin\Unipay\OnlineGateway;

use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\RequestInterface;
use Pengxul\Payf\Direction\ResponseDirection;
use Pengxul\Payf\Pay;
use Pengxul\Payf\Plugin\Unipay\OnlineGateway\WapPayPlugin;
use Pengxul\Payf\Provider\Unipay;
use Pengxul\Payf\Rocket;
use Pengxul\Payf\Tests\TestCase;

class WapPayPluginTest extends TestCase
{
    /**
     * @var \Pengxul\Payf\Plugin\Unipay\OnlineGateway\WapPayPlugin
     */
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new WapPayPlugin();
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams([]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $radar = $result->getRadar();
        $payload = $result->getPayload();

        self::assertInstanceOf(RequestInterface::class, $radar);
        self::assertEquals('POST', $radar->getMethod());
        self::assertEquals(new Uri(Unipay::URL[Pay::MODE_NORMAL].'gateway/api/frontTransReq.do'), $radar->getUri());
        self::assertEquals(ResponseDirection::class, $result->getDirection());
        self::assertEquals('000201', $payload['bizType']);
        self::assertEquals('01', $payload['txnType']);
        self::assertEquals('01', $payload['txnSubType']);
        self::assertEquals('08', $payload['channelType']);
    }
}
