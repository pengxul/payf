<?php

namespace Pengxul\Payf\Tests\Plugin\Wechat\Marketing\Coupon;

use GuzzleHttp\Psr7\Uri;
use Pengxul\Payf\Pay;
use Pengxul\Payf\Plugin\Wechat\Marketing\Coupon\QueryStocksPlugin;
use Pengxul\Payf\Provider\Wechat;
use Pengxul\Payf\Rocket;
use Pengxul\Payf\Tests\TestCase;
use Yansongda\Supports\Collection;

class QueryStocksPluginTest extends TestCase
{
    protected QueryStocksPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new QueryStocksPlugin();
    }

    public function testNormal()
    {
        $rocket = (new Rocket())->setParams([])->setPayload(new Collection([
            'limit' => 1,
        ]));

        $result = $this->plugin->assembly($rocket, function ($rocket) {return $rocket; });

        $radar = $result->getRadar();

        self::assertEquals('GET', $radar->getMethod());
        self::assertNull($result->getPayload());
        self::assertEquals(new Uri(Wechat::URL[Pay::MODE_NORMAL].'v3/marketing/favor/stocks?limit=1&stock_creator_mchid=1600314069'), $radar->getUri());
    }

    public function testExistMchId()
    {
        $rocket = (new Rocket())->setParams([])->setPayload(new Collection([
            'limit' => 1,
            'stock_creator_mchid' => '123',
        ]));

        $result = $this->plugin->assembly($rocket, function ($rocket) {return $rocket; });

        $radar = $result->getRadar();

        self::assertEquals(new Uri(Wechat::URL[Pay::MODE_NORMAL].'v3/marketing/favor/stocks?limit=1&stock_creator_mchid=123'), $radar->getUri());
    }
}
