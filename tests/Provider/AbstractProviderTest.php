<?php

namespace Pengxul\Payf\Tests\Provider;

use Closure;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Mockery;
use Psr\Http\Message\ResponseInterface;
use Pengxul\Payf\Contract\HttpClientInterface;
use Pengxul\Payf\Contract\PluginInterface;
use Pengxul\Payf\Contract\ShortcutInterface;
use Pengxul\Payf\Exception\Exception;
use Pengxul\Payf\Exception\InvalidConfigException;
use Pengxul\Payf\Direction\ArrayDirection;
use Pengxul\Payf\Direction\NoHttpRequestDirection;
use Pengxul\Payf\Pay;
use Pengxul\Payf\Provider\AbstractProvider;
use Pengxul\Payf\Rocket;
use Pengxul\Payf\Tests\TestCase;
use Yansongda\Supports\Collection;

class AbstractProviderTest extends TestCase
{
    public function testVerifyObjectPlugin()
    {
        $plugin = [new FooPlugin()];

        $provider = new FooProviderStub();
        $result = $provider->pay($plugin, []);

        self::assertInstanceOf(ResponseInterface::class, $result);
    }

    public function testVerifyCallablePlugin()
    {
        $plugin = [function ($rocket, $next) {
            $rocket->setDirection(NoHttpRequestDirection::class)
                ->setDestination(new Response());

            return $next($rocket);
        }];

        $provider = new FooProviderStub();
        $result = $provider->pay($plugin, []);

        self::assertInstanceOf(ResponseInterface::class, $result);
    }

    public function testVerifyNormalPlugin()
    {
        $plugin = [FooPlugin::class];

        $provider = new FooProviderStub();
        $result = $provider->pay($plugin, []);

        self::assertInstanceOf(ResponseInterface::class, $result);
    }

    public function testIgnite()
    {
        $response = new Response(200, [], 'yansongda/pay');
        $rocket = new Rocket();
        $rocket->setRadar(new Request('get', ''));

        $http = Mockery::mock(Client::class);
        $http->shouldReceive('sendRequest')->andReturn($response);

        Pay::set(HttpClientInterface::class, $http);

        $provider = new FooProviderStub();
        $result = $provider->ignite($rocket);

        self::assertEquals('yansongda/pay', (string) $result->getDestination()->getBody());
    }

    public function testIgnitePreRead()
    {
        $response = new Response(200, [], 'yansongda/pay');
        $response->getBody()->read(1);

        $rocket = new Rocket();
        $rocket->setRadar(new Request('get', ''));

        $http = Mockery::mock(Client::class);
        $http->shouldReceive('sendRequest')->andReturn($response);

        Pay::set(HttpClientInterface::class, $http);

        $provider = new FooProviderStub();
        $result = $provider->ignite($rocket);

        self::assertEquals('yansongda/pay', (string) $result->getDestination()->getBody());
    }

    public function testIgniteWrongHttpClient()
    {
        $rocket = new Rocket();
        $rocket->setRadar(new Request('get', ''));

        Pay::set(HttpClientInterface::class, new Collection());

        self::expectException(InvalidConfigException::class);
        self::expectExceptionCode(Exception::HTTP_CLIENT_CONFIG_ERROR);

        $provider = new FooProviderStub();
        $provider->ignite($rocket);
    }

    public function testArrayDirection()
    {
        $response = new Response(200, [], '{"name":"yansongda"}');

        $http = Mockery::mock(Client::class);
        $http->shouldReceive('sendRequest')->andReturn($response);

        Pay::set(HttpClientInterface::class, $http);

        $plugin = [BarPlugin::class];

        $provider = new FooProviderStub();
        $result = $provider->pay($plugin, []);

        self::assertIsArray($result);
    }

    public function testNoCommonPlugins()
    {
        $provider = new Foo2ProviderStub();
        $result = $provider->call(FooShortcut::class, ['_no_common_plugins' => true]);

        self::assertInstanceOf(ResponseInterface::class, $result);
    }
}

class FooProviderStub extends AbstractProvider
{
    public function find($order): Collection
    {
        return new Collection();
    }

    public function cancel($order): Collection
    {
        return new Collection();
    }

    public function close($order): Collection
    {
        return new Collection();
    }

    public function refund(array $order): Collection
    {
        return new Collection();
    }

    public function callback($contents = null, ?array $params = null): Collection
    {
        return new Collection();
    }

    public function success(): ResponseInterface
    {
        return new Response(
            200,
            ['Content-Type' => 'application/json'],
            json_encode(['code' => 'SUCCESS', 'message' => '成功']),
        );
    }

    public function mergeCommonPlugins(array $plugins): array
    {
        return [];
    }
}

class Foo2ProviderStub extends FooProviderStub
{
    public function mergeCommonPlugins(array $plugins): array
    {
        return [new BarPlugin()];
    }
}

class FooPlugin implements PluginInterface
{
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        $rocket->setDirection(NoHttpRequestDirection::class)
            ->setDestination(new Response());

        return $next($rocket);
    }
}

class BarPlugin implements PluginInterface
{
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        $rocket->setDirection(ArrayDirection::class)
            ->setRadar(new Request('get', ''));

        $rocket = $next($rocket);

        $rocket->setDestination(new Collection(['name' => 'yansongda']));

        return $rocket;
    }
}

class FooShortcut implements ShortcutInterface
{
    public function getPlugins(array $params): array
    {
        return [FooPlugin::class];
    }
}
