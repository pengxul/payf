<?php

declare(strict_types=1);

namespace Pengxul\Payf\Plugin\Wechat\Shortcut;

use Pengxul\Payf\Contract\ShortcutInterface;
use Pengxul\Payf\Plugin\Wechat\Pay\App\InvokePrepayPlugin;
use Pengxul\Payf\Plugin\Wechat\Pay\App\PrepayPlugin;

class AppShortcut implements ShortcutInterface
{
    public function getPlugins(array $params): array
    {
        return [
            PrepayPlugin::class,
            InvokePrepayPlugin::class,
        ];
    }
}
