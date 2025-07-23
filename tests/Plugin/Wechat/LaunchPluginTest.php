<?php

namespace Pengxul\Payf\Tests\Plugin\Wechat;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use Pengxul\Payf\Exception\Exception;
use Pengxul\Payf\Exception\InvalidResponseException;
use Pengxul\Payf\Direction\NoHttpRequestDirection;
use Pengxul\Payf\Direction\OriginResponseDirection;
use Pengxul\Payf\Plugin\Wechat\LaunchPlugin;
use Pengxul\Payf\Rocket;
use Pengxul\Payf\Tests\TestCase;
use Yansongda\Supports\Collection;

class LaunchPluginTest extends TestCase
{
    /**
     * @var \Pengxul\Payf\Plugin\Wechat\LaunchPlugin
     */
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new LaunchPlugin();
    }

    public function testShouldNotDoRequest()
    {
        $rocket = new Rocket();
        $rocket->setDirection(NoHttpRequestDirection::class);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertSame($rocket, $result);
    }

    public function testOriginalResponseDestination()
    {
        $destination = new Response();

        $rocket = new Rocket();
        $rocket->setDirection(OriginResponseDirection::class);
        $rocket->setDestination($destination);
        $rocket->setDestinationOrigin(new ServerRequest('POST', 'http://localhost'));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertSame($destination, $result->getDestination());
    }

    public function testOriginalResponseCodeErrorDestination()
    {
        $destination = new Response(500);

        $rocket = new Rocket();
        $rocket->setDirection(OriginResponseDirection::class);
        $rocket->setDestination($destination);
        $rocket->setDestinationOrigin(new ServerRequest('POST', 'http://localhost'));

        self::expectException(InvalidResponseException::class);
        self::expectExceptionCode(Exception::INVALID_RESPONSE_CODE);

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }

    public function testArrayDestination()
    {
        $destination = [];

        $rocket = new Rocket();
        $rocket->setDirection(OriginResponseDirection::class);
        $rocket->setDestination($destination);
        $rocket->setDestinationOrigin(new ServerRequest('POST', 'http://localhost'));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals($destination, $result->getDestination());
    }

    public function testCollectionDestination()
    {
        $destination = new Collection();

        $rocket = new Rocket();
        $rocket->setDirection(OriginResponseDirection::class);
        $rocket->setDestination($destination);
        $rocket->setDestinationOrigin(new ServerRequest('POST', 'http://localhost'));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertSame($destination, $result->getDestination());
    }
}
