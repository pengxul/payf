<?php

namespace Pengxul\Payf\Tests\Plugin\Wechat\Fund\Transfer;

use GuzzleHttp\Psr7\Uri;
use Pengxul\Payf\Exception\Exception;
use Pengxul\Payf\Exception\InvalidParamsException;
use Pengxul\Payf\Pay;
use Pengxul\Payf\Plugin\Wechat\Fund\Transfer\QueryBatchDetailIdPlugin;
use Pengxul\Payf\Provider\Wechat;
use Pengxul\Payf\Rocket;
use Pengxul\Payf\Tests\TestCase;
use Yansongda\Supports\Collection;

class QueryBatchDetailIdPluginTest extends TestCase
{
    /**
     * @var \Pengxul\Payf\Plugin\Wechat\Fund\Transfer\QueryBatchDetailIdPlugin
     */
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new QueryBatchDetailIdPlugin();
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams([])->setPayload(new Collection(['batch_id' => '123', 'detail_id' => '456']));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $radar = $result->getRadar();

        self::assertEquals(new Uri(Wechat::URL[Pay::MODE_NORMAL].'v3/transfer/batches/batch-id/123/details/detail-id/456'), $radar->getUri());
        self::assertEquals('GET', $radar->getMethod());
    }

    public function testNormalNoBatchId()
    {
        $rocket = new Rocket();
        $rocket->setParams([])->setPayload(new Collection(['detail' => '456']));

        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::MISSING_NECESSARY_PARAMS);

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }

    public function testNormalNoDetailId()
    {
        $rocket = new Rocket();
        $rocket->setParams([])->setPayload(new Collection(['batch_id' => '123']));

        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::MISSING_NECESSARY_PARAMS);

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }

    public function testPartner()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_config' => 'service_provider'])->setPayload(new Collection(['batch_id' => '123', 'detail_id' => '456']));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $radar = $result->getRadar();

        self::assertEquals(new Uri(Wechat::URL[Pay::MODE_NORMAL].'v3/partner-transfer/batches/batch-id/123/details/detail-id/456'), $radar->getUri());
        self::assertEquals('GET', $radar->getMethod());
    }

    public function testPartnerNoBatchId()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_config' => 'service_provider'])->setPayload(new Collection(['detail' => '456']));

        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::MISSING_NECESSARY_PARAMS);

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }

    public function testPartnerNoDetailId()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_config' => 'service_provider'])->setPayload(new Collection(['batch_id' => '123']));

        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::MISSING_NECESSARY_PARAMS);

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }
}
