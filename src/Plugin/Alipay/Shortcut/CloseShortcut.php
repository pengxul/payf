<?php

declare(strict_types=1);

namespace Pengxul\Payf\Plugin\Alipay\Shortcut;

use Pengxul\Payf\Contract\ShortcutInterface;
use Pengxul\Payf\Plugin\Alipay\Trade\ClosePlugin;

class CloseShortcut implements ShortcutInterface
{
    public function getPlugins(array $params): array
    {
        return [
            ClosePlugin::class,
        ];
    }
}
