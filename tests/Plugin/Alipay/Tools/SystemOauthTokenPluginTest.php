<?php

namespace Pengxul\Payf\Tests\Plugin\Alipay\Tools;

use Pengxul\Payf\Direction\ResponseDirection;
use Pengxul\Payf\Plugin\Alipay\Tools\SystemOauthTokenPlugin;
use Pengxul\Payf\Rocket;
use Pengxul\Payf\Tests\TestCase;

class SystemOauthTokenPluginTest extends TestCase
{
    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams(['name' => 'yansongda', 'age' => 28]);

        $plugin = new SystemOauthTokenPlugin();

        $result = $plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals('yansongda', $result->getPayload()->get('name'));
        self::assertEquals(28, $result->getPayload()->get('age'));
    }
}
