<?php

namespace Pengxul\Payf\Tests\Plugin\Unipay\OnlineGateway;

use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\RequestInterface;
use Pengxul\Payf\Pay;
use Pengxul\Payf\Plugin\Unipay\OnlineGateway\QueryPlugin;
use Pengxul\Payf\Provider\Unipay;
use Pengxul\Payf\Rocket;
use Pengxul\Payf\Tests\TestCase;

class QueryPluginTest extends TestCase
{
    /**
     * @var \Pengxul\Payf\Plugin\Unipay\OnlineGateway\QueryPlugin
     */
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new QueryPlugin();
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
        self::assertEquals(new Uri(Unipay::URL[Pay::MODE_NORMAL].'gateway/api/queryTrans.do'), $radar->getUri());
        self::assertEquals('000000', $payload['bizType']);
        self::assertEquals('00', $payload['txnType']);
        self::assertEquals('00', $payload['txnSubType']);
    }
}
