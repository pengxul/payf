<?php

namespace Pengxul\Payf\Tests\Plugin\Wechat\Risk\Complaints;

use GuzzleHttp\Psr7\Uri;
use Pengxul\Payf\Exception\Exception;
use Pengxul\Payf\Exception\InvalidParamsException;
use Pengxul\Payf\Direction\OriginResponseDirection;
use Pengxul\Payf\Plugin\Wechat\Risk\Complaints\DownloadMediaPlugin;
use Pengxul\Payf\Rocket;
use Pengxul\Payf\Tests\TestCase;
use Yansongda\Supports\Collection;

class DownloadMediaPluginTest extends TestCase
{
    /**
     * @var \Pengxul\Payf\Plugin\Wechat\Risk\Complaints\DownloadMediaPlugin
     */
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new DownloadMediaPlugin();
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams([])->setPayload(new Collection(['media_url' => 'https://yansongda.cn']));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $radar = $result->getRadar();

        self::assertEquals(OriginResponseDirection::class, $result->getDirection());
        self::assertEquals('GET', $radar->getMethod());
        self::assertEquals(new Uri('https://yansongda.cn'), $radar->getUri());
    }

    public function testNormalNoDownloadUrl()
    {
        $rocket = new Rocket();
        $rocket->setParams([])->setPayload(new Collection());

        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::MISSING_NECESSARY_PARAMS);

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }
}
