<?php

declare(strict_types=1);

namespace Pengxul\Payf\Plugin\Wechat\Shortcut;

use Pengxul\Payf\Contract\ShortcutInterface;
use Pengxul\Payf\Plugin\Wechat\Fund\Transfer\CreatePlugin;

class TransferShortcut implements ShortcutInterface
{
    public function getPlugins(array $params): array
    {
        return [
            CreatePlugin::class,
        ];
    }
}
