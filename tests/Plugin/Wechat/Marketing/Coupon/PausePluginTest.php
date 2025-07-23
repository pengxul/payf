<?php

namespace Pengxul\Payf\Tests\Plugin\Wechat\Marketing\Coupon;

use GuzzleHttp\Psr7\Uri;
use Pengxul\Payf\Pay;
use Pengxul\Payf\Plugin\Wechat\Marketing\Coupon\PausePlugin;
use Pengxul\Payf\Provider\Wechat;
use Pengxul\Payf\Rocket;
use Pengxul\Payf\Tests\TestCase;
use Yansongda\Supports\Collection;

class PausePluginTest extends TestCase
{
    protected PausePlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new PausePlugin();
    }

    public function testNormal()
    {
        $rocket = (new Rocket())->setParams([])->setPayload(new Collection([
            'stock_id' => '7890',
        ]));

        $result = $this->plugin->assembly($rocket, function ($rocket) {return $rocket; });

        $radar = $result->getRadar();

        self::assertEquals('POST', $radar->getMethod());
        self::assertEquals(new Uri(Wechat::URL[Pay::MODE_NORMAL].'v3/marketing/favor/stocks/7890/pause'), $radar->getUri());
        self::assertEquals([
            'stock_creator_mchid' => '1600314069',
        ], $result->getPayload()->all());
    }
}
