<?php

namespace Pengxul\Payf\Tests\Plugin\Wechat\Fund\Transfer;

use Pengxul\Payf\Exception\Exception;
use Pengxul\Payf\Exception\InvalidParamsException;
use Pengxul\Payf\Plugin\Wechat\Fund\Transfer\QueryDetailReceiptPlugin;
use Pengxul\Payf\Rocket;
use Pengxul\Payf\Tests\TestCase;
use Yansongda\Supports\Collection;

class QueryDetailReceiptPluginTest extends TestCase
{
    /**
     * @var \Pengxul\Payf\Plugin\Wechat\Fund\Transfer\QueryDetailReceiptPlugin
     */
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new QueryDetailReceiptPlugin();
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams([])->setPayload(new Collection(['out_detail_no' => '123', 'accept_type' => '456']));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $radar = $result->getRadar();
        $url = $radar->getUri();

        self::assertEquals('/v3/transfer-detail/electronic-receipts', $url->getPath());
        self::assertStringContainsString('out_detail_no=123', $url->getQuery());
        self::assertStringContainsString('accept_type=456', $url->getQuery());
        self::assertEquals('GET', $radar->getMethod());
    }

    public function testNormalNoOutDetailNo()
    {
        $rocket = new Rocket();
        $rocket->setParams([])->setPayload(new Collection(['accept_type' => '456']));

        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::MISSING_NECESSARY_PARAMS);

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }

    public function testNormalNoAcceptType()
    {
        $rocket = new Rocket();
        $rocket->setParams([])->setPayload(new Collection(['out_detail_no' => '123']));

        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::MISSING_NECESSARY_PARAMS);

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }
}
