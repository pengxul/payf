<?php

namespace Pengxul\Payf\Tests\Plugin\Wechat;

use Pengxul\Payf\Plugin\Wechat\PreparePlugin;
use Pengxul\Payf\Rocket;
use Pengxul\Payf\Tests\TestCase;

class PreparePluginTest extends TestCase
{
    /**
     * @var \Pengxul\Payf\Plugin\Wechat\PreparePlugin
     */
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new PreparePlugin();
    }

    public function testNormal()
    {
        $params = [
            'name' => 'yansongda',
            '_aaa' => 'aaa',
        ];

        $rocket = (new Rocket())->setParams($params);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
        $payload = $result->getPayload()->all();

        self::assertEquals('yansongda', $payload['name']);
        self::assertArrayNotHasKey('aaa', $payload);
        self::assertArrayNotHasKey('_aaa', $payload);
    }
}
