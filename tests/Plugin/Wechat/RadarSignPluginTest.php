<?php

namespace Pengxul\Payf\Tests\Plugin\Wechat;

use GuzzleHttp\Psr7\Request;
use ReflectionClass;
use Pengxul\Payf\Contract\ConfigInterface;
use Pengxul\Payf\Exception\Exception;
use Pengxul\Payf\Exception\InvalidConfigException;
use Pengxul\Payf\Packer\JsonPacker;
use Pengxul\Payf\Packer\XmlPacker;
use Pengxul\Payf\Pay;
use Pengxul\Payf\Plugin\Wechat\RadarSignPlugin;
use Pengxul\Payf\Rocket;
use Pengxul\Payf\Tests\TestCase;
use Yansongda\Supports\Collection;
use Yansongda\Supports\Str;

class RadarSignPluginTest extends TestCase
{
    protected RadarSignPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new RadarSignPlugin();
    }

    public function testNormal()
    {
        $params = [
            'name' => 'yansongda',
            'age' => 28,
        ];
        $rocket = (new Rocket())->setParams($params)
                                ->setPayload(new Collection($params))
                                ->setRadar(new Request('GET', '127.0.0.1'));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
        $radar = $result->getRadar();

        self::assertTrue($radar->hasHeader('Authorization'));
        self::assertFalse($radar->hasHeader('Wechatpay-Serial'));
        self::assertEquals(json_encode($params), (string) $radar->getBody());
    }

    public function testNormalConstruct()
    {
        $this->plugin = new RadarSignPlugin(new JsonPacker(), new XmlPacker());

        $params = [
            'name' => 'yansongda',
            'age' => 28,
        ];
        $rocket = (new Rocket())->setParams($params)
                                ->setPayload(new Collection($params))
                                ->setRadar(new Request('GET', '127.0.0.1'));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
        $radar = $result->getRadar();

        self::assertTrue($radar->hasHeader('Authorization'));
        self::assertFalse($radar->hasHeader('Wechatpay-Serial'));
        self::assertEquals(json_encode($params), (string) $radar->getBody());
    }

    public function testNormalWithWechatSerial()
    {
        $params = [
            '_serial_no' => 'yansongda',
            'name' => 'yansongda',
            'age' => 28,
        ];
        $rocket = (new Rocket())->setParams($params)
            ->setPayload(new Collection($params))
            ->setRadar(new Request('GET', '127.0.0.1'));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
        $radar = $result->getRadar();

        self::assertTrue($radar->hasHeader('Authorization'));
        self::assertTrue($radar->hasHeader('Wechatpay-Serial'));
    }

    public function testGetWechatAuthorization()
    {
        $params = [
            'out_trade_no' => 1626493236,
            'description' => 'yansongda 测试 - 1626493236',
            'amount' => [
                'total' => 1,
            ],
            'scene_info' => [
                'payer_client_ip' => '127.0.0.1',
                'h5_info' => [
                    'type' => 'Wap',
                ]
            ]];
        $timestamp = 1626493236;
        $random = 'QqtzdVzxavZeXag9G5mtfzbfzFMf89p6';
        $contents = "POST\n/v3/pay/transactions/h5\n1626493236\nQqtzdVzxavZeXag9G5mtfzbfzFMf89p6\n{\"out_trade_no\":1626493236,\"description\":\"yansongda 测试 - 1626493236\",\"amount\":{\"total\":1},\"scene_info\":{\"payer_client_ip\":\"127.0.0.1\",\"h5_info\":{\"type\":\"Wap\"}},\"appid\":\"wx55955316af4ef13\",\"mchid\":\"1600314069\",\"notify_url\":\"http:\/\/127.0.0.1:8000\/wechat\/notify\"}\n";

        $class = new ReflectionClass($this->plugin);
        $method = $class->getMethod('v3GetWechatAuthorization');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->plugin, [$params, $timestamp, $random, $contents]);

        self::assertEquals(
            'WECHATPAY2-SHA256-RSA2048 mchid="1600314069",nonce_str="QqtzdVzxavZeXag9G5mtfzbfzFMf89p6",timestamp="1626493236",serial_no="25F8AA5452D55497C24BA57DC81B1E5915DC2E77",signature="KzIgMgiop3nQJNdBVR2Xah/JUwVBLDFFajyXPiSN8b8YAYEA4FuWfaCgFJ52+WFed+PhOYWx/ZPih4RaEuuSdYB8eZwYUx7RZGMQZk0bKCctAjjPuf4pJN+f/WsXKjPIy3diqF5x7gyxwSCaKWP4/KjsHNqgQpiC8q1uC5xmElzuhzSwj88LIoLtkAuSmtUVvdAt0Nz41ECHZgHWSGR32TfBo902r8afdaVKkFde8IoqcEJJcp6sMxdDO5l9R5KEWxrJ1SjsXVrb0IPH8Nj7e6hfhq7pucxojPpzsC+ZWAYvufZkAQx3kTiFmY87T+QhkP9FesOfWvkIRL4E6MP6ug=="',
            $result
        );
    }

    public function testGetWechatAuthorizationMissingMchPublicCert()
    {
        $params = [
            'out_trade_no' => 1626493236,
            'description' => 'yansongda 测试 - 1626493236',
            'amount' => [
                'total' => 1,
            ],
            'scene_info' => [
                'payer_client_ip' => '127.0.0.1',
                'h5_info' => [
                    'type' => 'Wap',
                ]
            ]];
        $timestamp = 1626493236;
        $random = 'QqtzdVzxavZeXag9G5mtfzbfzFMf89p6';
        $contents = "POST\n/v3/pay/transactions/h5\n1626493236\nQqtzdVzxavZeXag9G5mtfzbfzFMf89p6\n{\"out_trade_no\":1626493236,\"description\":\"yansongda 测试 - 1626493236\",\"amount\":{\"total\":1},\"scene_info\":{\"payer_client_ip\":\"127.0.0.1\",\"h5_info\":{\"type\":\"Wap\"}},\"appid\":\"wx55955316af4ef13\",\"mchid\":\"1600314069\",\"notify_url\":\"http:\/\/127.0.0.1:8000\/wechat\/notify\"}\n";

        $config = Pay::get(ConfigInterface::class);
        $config->set('wechat.default.mch_public_cert_path', null);

        self::expectException(InvalidConfigException::class);
        self::expectExceptionCode(Exception::WECHAT_CONFIG_ERROR);
        self::expectExceptionMessage('Missing Wechat Config -- [mch_public_cert_path]');

        $class = new ReflectionClass($this->plugin);
        $method = $class->getMethod('v3GetWechatAuthorization');
        $method->setAccessible(true);
        $method->invokeArgs($this->plugin, [$params, $timestamp, $random, $contents]);
    }

    public function testGetWechatAuthorizationWrongMchPublicCert()
    {
        $params = [
            'out_trade_no' => 1626493236,
            'description' => 'yansongda 测试 - 1626493236',
            'amount' => [
                'total' => 1,
            ],
            'scene_info' => [
                'payer_client_ip' => '127.0.0.1',
                'h5_info' => [
                    'type' => 'Wap',
                ]
            ]];
        $timestamp = 1626493236;
        $random = 'QqtzdVzxavZeXag9G5mtfzbfzFMf89p6';
        $contents = "POST\n/v3/pay/transactions/h5\n1626493236\nQqtzdVzxavZeXag9G5mtfzbfzFMf89p6\n{\"out_trade_no\":1626493236,\"description\":\"yansongda 测试 - 1626493236\",\"amount\":{\"total\":1},\"scene_info\":{\"payer_client_ip\":\"127.0.0.1\",\"h5_info\":{\"type\":\"Wap\"}},\"appid\":\"wx55955316af4ef13\",\"mchid\":\"1600314069\",\"notify_url\":\"http:\/\/127.0.0.1:8000\/wechat\/notify\"}\n";

        $config = Pay::get(ConfigInterface::class);
        $config->set('wechat.default.mch_public_cert_path', __DIR__.'/../../Cert/foo');

        self::expectException(InvalidConfigException::class);
        self::expectExceptionCode(Exception::WECHAT_CONFIG_ERROR);
        self::expectExceptionMessage('Parse [mch_public_cert_path] Serial Number Error');

        $class = new ReflectionClass($this->plugin);
        $method = $class->getMethod('v3GetWechatAuthorization');
        $method->setAccessible(true);
        $method->invokeArgs($this->plugin, [$params, $timestamp, $random, $contents]);
    }

    public function testV2Normal()
    {
        $params = [
            'name' => 'yansongda',
            'age' => 28,
        ];
        $rocket = (new Rocket())->setParams(array_merge($params, ['_version' => 'v2']))
                                ->setPayload(new Collection($params))
                                ->setRadar(new Request('POST', '127.0.0.1'));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $radar = $result->getRadar();
        $payload = $result->getPayload();

        self::assertTrue($payload->has('sign'));
        self::assertTrue($payload->has('nonce_str'));
        self::assertTrue(Str::contains((string) $radar->getBody(), '<xml>'));
        self::assertTrue(Str::contains((string) $radar->getBody(), '<sign>'));
        self::assertTrue(Str::contains((string) $radar->getBody(), '<nonce_str>'));
    }
}
