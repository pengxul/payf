<?php

namespace Pengxul\Payf\Tests\Plugin\Wechat\Ecommerce\Refund;

use GuzzleHttp\Psr7\Uri;
use Pengxul\Payf\Exception\Exception;
use Pengxul\Payf\Exception\InvalidParamsException;
use Pengxul\Payf\Pay;
use Pengxul\Payf\Plugin\Wechat\Ecommerce\Refund\QueryReturnAdvancePlugin;
use Pengxul\Payf\Provider\Wechat;
use Pengxul\Payf\Rocket;
use Pengxul\Payf\Tests\TestCase;
use Yansongda\Supports\Collection;

class QueryReturnAdvancePluginTest extends TestCase
{
    /**
     * @var \Pengxul\Payf\Plugin\Wechat\Ecommerce\Refund\QueryReturnAdvancePlugin
     */
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new QueryReturnAdvancePlugin();
    }

    public function testNotInServiceMode()
    {
        $rocket = new Rocket();
        $rocket->setParams([])->setPayload(new Collection());

        $this->expectException(InvalidParamsException::class);
        $this->expectExceptionCode(Exception::NOT_IN_SERVICE_MODE);

        $this->plugin->assembly($rocket, function ($rocket) {return $rocket; });
    }

    public function testMissingRefundId()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_config' => 'service_provider'])->setPayload(new Collection());

        $this->expectException(InvalidParamsException::class);
        $this->expectExceptionCode(Exception::MISSING_NECESSARY_PARAMS);

        $this->plugin->assembly($rocket, function ($rocket) {return $rocket; });
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_config' => 'service_provider'])->setPayload(new Collection(['refund_id' => '123']));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $radar = $result->getRadar();

        self::assertEquals(new Uri(Wechat::URL[Pay::MODE_SERVICE].'v3/ecommerce/refunds/123/return-advance?sub_mchid=1600314070'), $radar->getUri());
        self::assertEquals('GET', $radar->getMethod());
    }
}
