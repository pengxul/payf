<?php

namespace Pengxul\Payf\Tests\Plugin\Wechat\Fund\Transfer;

use Pengxul\Payf\Exception\Exception;
use Pengxul\Payf\Exception\InvalidParamsException;
use Pengxul\Payf\Plugin\Wechat\Fund\Transfer\QueryOutBatchNoPlugin;
use Pengxul\Payf\Rocket;
use Pengxul\Payf\Tests\TestCase;
use Yansongda\Supports\Collection;

class QueryOutBatchNoPluginTest extends TestCase
{
    /**
     * @var \Pengxul\Payf\Plugin\Wechat\Fund\Transfer\QueryOutBatchNoPlugin
     */
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new QueryOutBatchNoPlugin();
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams([])->setPayload(new Collection(['out_batch_no' => '123', 'need_query_detail' => false]));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $radar = $result->getRadar();
        $url = $radar->getUri();

        self::assertEquals('/v3/transfer/batches/out-batch-no/123', $url->getPath());
        self::assertEquals('need_query_detail=0', $url->getQuery());
        self::assertEquals('GET', $radar->getMethod());
    }

    public function testNormalNoOutBatchNo()
    {
        $rocket = new Rocket();
        $rocket->setParams([])->setPayload(new Collection(['need_query_detail' => false]));

        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::MISSING_NECESSARY_PARAMS);

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }

    public function testNormalNoNeedQueryDetail()
    {
        $rocket = new Rocket();
        $rocket->setParams([])->setPayload(new Collection(['out_batch_no' => '123']));

        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::MISSING_NECESSARY_PARAMS);

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }

    public function testPartner()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_config' => 'service_provider'])->setPayload(new Collection(['out_batch_no' => '123', 'need_query_detail' => false]));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $radar = $result->getRadar();
        $url = $radar->getUri();

        self::assertEquals('/v3/partner-transfer/batches/out-batch-no/123', $url->getPath());
        self::assertEquals('need_query_detail=0', $url->getQuery());
        self::assertEquals('GET', $radar->getMethod());
    }

    public function testPartnerNoOutBatchNo()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_config' => 'service_provider'])->setPayload(new Collection(['need_query_detail' => false]));

        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::MISSING_NECESSARY_PARAMS);

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }

    public function testPartnerNoNeedQueryDetail()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_config' => 'service_provider'])->setPayload(new Collection(['out_batch_no' => '123']));

        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::MISSING_NECESSARY_PARAMS);

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }
}
