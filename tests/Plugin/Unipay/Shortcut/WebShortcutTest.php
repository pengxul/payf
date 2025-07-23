<?php

declare(strict_types=1);

namespace Pengxul\Payf\Tests\Plugin\Unipay\Shortcut;

use Pengxul\Payf\Plugin\Unipay\HtmlResponsePlugin;
use Pengxul\Payf\Plugin\Unipay\OnlineGateway\PagePayPlugin;
use Pengxul\Payf\Plugin\Unipay\Shortcut\WebShortcut;
use Pengxul\Payf\Tests\TestCase;

class WebShortcutTest extends TestCase
{
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new WebShortcut();
    }

    public function test()
    {
        self::assertEquals([
            PagePayPlugin::class,
            HtmlResponsePlugin::class,
        ], $this->plugin->getPlugins([]));
    }
}
