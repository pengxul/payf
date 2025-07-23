<?php

declare(strict_types=1);

namespace Pengxul\Payf\Plugin\Unipay\Shortcut;

use Pengxul\Payf\Contract\ShortcutInterface;
use Pengxul\Payf\Plugin\Unipay\HtmlResponsePlugin;
use Pengxul\Payf\Plugin\Unipay\OnlineGateway\WapPayPlugin;

class WapShortcut implements ShortcutInterface
{
    public function getPlugins(array $params): array
    {
        return [
            WapPayPlugin::class,
            HtmlResponsePlugin::class,
        ];
    }
}
