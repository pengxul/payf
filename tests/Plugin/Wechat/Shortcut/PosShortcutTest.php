<?php

declare(strict_types=1);

namespace Pengxul\Payf\Tests\Plugin\Wechat\Shortcut;

use Pengxul\Payf\Plugin\Wechat\Pay\Pos\PayPlugin;
use Pengxul\Payf\Plugin\Wechat\Shortcut\PosShortcut;
use Pengxul\Payf\Tests\TestCase;

class PosShortcutTest extends TestCase
{
    protected PosShortcut $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new PosShortcut();
    }

    public function testDefault()
    {
        self::assertEquals([
            PayPlugin::class,
        ], $this->plugin->getPlugins([]));
    }
}
