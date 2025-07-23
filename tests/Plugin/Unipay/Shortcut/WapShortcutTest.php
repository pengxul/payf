<?php

declare(strict_types=1);

namespace Pengxul\Payf\Tests\Plugin\Unipay\Shortcut;

use Pengxul\Payf\Plugin\Unipay\HtmlResponsePlugin;
use Pengxul\Payf\Plugin\Unipay\OnlineGateway\WapPayPlugin;
use Pengxul\Payf\Plugin\Unipay\Shortcut\WapShortcut;
use Pengxul\Payf\Tests\TestCase;

class WapShortcutTest extends TestCase
{
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new WapShortcut();
    }

    public function test()
    {
        self::assertEquals([
            WapPayPlugin::class,
            HtmlResponsePlugin::class,
        ], $this->plugin->getPlugins([]));
    }
}
