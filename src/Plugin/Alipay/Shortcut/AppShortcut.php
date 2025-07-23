<?php

declare(strict_types=1);

namespace Pengxul\Payf\Plugin\Alipay\Shortcut;

use Closure;
use GuzzleHttp\Psr7\Response;
use Pengxul\Payf\Contract\PluginInterface;
use Pengxul\Payf\Contract\ShortcutInterface;
use Pengxul\Payf\Plugin\Alipay\Trade\AppPayPlugin;
use Pengxul\Payf\Rocket;
use Yansongda\Supports\Arr;
use Yansongda\Supports\Collection;

class AppShortcut implements ShortcutInterface
{
    public function getPlugins(array $params): array
    {
        return [
            AppPayPlugin::class,
            $this->buildResponse(),
        ];
    }

    protected function buildResponse(): PluginInterface
    {
        return new class() implements PluginInterface {
            public function assembly(Rocket $rocket, Closure $next): Rocket
            {
                $rocket->setDestination(new Response());

                /* @var Rocket $rocket */
                $rocket = $next($rocket);

                $response = $this->buildHtml($rocket->getPayload());

                return $rocket->setDestination($response);
            }

            protected function buildHtml(Collection $payload): Response
            {
                return new Response(200, [], Arr::query($payload->all()));
            }
        };
    }
}
