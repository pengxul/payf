<?php

namespace Pengxul\Payf\Tests\Plugin;

use Pengxul\Payf\Contract\DirectionInterface;
use Pengxul\Payf\Exception\InvalidConfigException;
use Pengxul\Payf\Direction\NoHttpRequestDirection;
use Pengxul\Payf\Pay;
use Pengxul\Payf\Plugin\ParserPlugin;
use Pengxul\Payf\Rocket;
use Pengxul\Payf\Tests\Stubs\FooPackerStub;
use Pengxul\Payf\Tests\Stubs\FooParserStub;
use Pengxul\Payf\Tests\TestCase;

class ParserPluginTest extends TestCase
{
    protected ParserPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new ParserPlugin();
    }

    public function testWrongParser()
    {
        self::expectException(InvalidConfigException::class);
        self::expectExceptionCode(InvalidConfigException::INVALID_PARSER);

        $rocket = new Rocket();
        $rocket->setDirection(FooParserStub::class);

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }

    public function testWrongPacker()
    {
        self::expectException(InvalidConfigException::class);
        self::expectExceptionCode(InvalidConfigException::INVALID_PACKER);

        $rocket = new Rocket();
        $rocket->setPacker(FooPackerStub::class);

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }

    public function testDefaultParser()
    {
        Pay::set(DirectionInterface::class, NoHttpRequestDirection::class);

        $rocket = new Rocket();

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertSame($rocket, $result);
    }

    public function testObjectParser()
    {
        Pay::set(DirectionInterface::class, new NoHttpRequestDirection());

        $rocket = new Rocket();

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertSame($rocket, $result);
    }
}
