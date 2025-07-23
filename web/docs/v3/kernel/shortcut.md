# 💤快捷方式

Shortcut 即快捷方式，是一系列 Plugin 的组合，方便我们使用 Pay。

## 定义

```php
<?php

declare(strict_types=1);

namespace Pengxul\Payf\Contract;

interface ShortcutInterface
{
    /**
     * @author yansongda <me@yansongda.cn>
     *
     * @return \Pengxul\Payf\Contract\PluginInterface[]|string[]
     */
    public function getPlugins(array $params): array;
}
```

## 详细说明

以我们刚刚在 [插件Plugin](/docs/v3/kernel/plugin.md) 中的例子来说明，
支付宝电脑支付，其实也是一种 快捷方式

```php
<?php

declare(strict_types=1);

namespace Pengxul\Payf\Plugin\Alipay\Shortcut;

use Pengxul\Payf\Contract\ShortcutInterface;
use Pengxul\Payf\Plugin\Alipay\HtmlResponsePlugin;
use Pengxul\Payf\Plugin\Alipay\Trade\PagePayPlugin;

class WebShortcut implements ShortcutInterface
{
    public function getPlugins(array $params): array
    {
        return [
            PagePayPlugin::class,
            HtmlResponsePlugin::class,
        ];
    }
}
```

是不是灰常简单？
