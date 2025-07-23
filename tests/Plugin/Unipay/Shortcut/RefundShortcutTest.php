<?php

declare(strict_types=1);

namespace Pengxul\Payf\Tests\Plugin\Unipay\Shortcut;

use Pengxul\Payf\Exception\InvalidParamsException;
use Pengxul\Payf\Plugin\Unipay\OnlineGateway\RefundPlugin;
use Pengxul\Payf\Plugin\Unipay\Shortcut\RefundShortcut;
use Pengxul\Payf\Tests\TestCase;

class RefundShortcutTest extends TestCase
{
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new RefundShortcut();
    }

    public function testDefault()
    {
        self::assertEquals([
            RefundPlugin::class,
        ], $this->plugin->getPlugins([]));
    }

    public function testQrCode()
    {
        self::assertEquals([
            \Pengxul\Payf\Plugin\Unipay\QrCode\RefundPlugin::class,
        ], $this->plugin->getPlugins(['_action' => 'qr_code']));
    }

    public function testFoo()
    {
        $this->expectException(InvalidParamsException::class);
        $this->expectExceptionMessage('Refund action [fooPlugins] not supported');

        $this->plugin->getPlugins(['_action' => 'foo']);
    }
}
