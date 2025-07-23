<?php

declare(strict_types=1);

namespace Pengxul\Payf\Tests\Plugin\Unipay\Shortcut;

use Pengxul\Payf\Exception\Exception;
use Pengxul\Payf\Exception\InvalidParamsException;
use Pengxul\Payf\Plugin\Unipay\OnlineGateway\CancelPlugin;
use Pengxul\Payf\Plugin\Unipay\Shortcut\CancelShortcut;
use Pengxul\Payf\Tests\TestCase;

class CancelShortcutTest extends TestCase
{
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new CancelShortcut();
    }

    public function testDefault()
    {
        self::assertEquals([
            CancelPlugin::class,
        ], $this->plugin->getPlugins([]));
    }

    public function testQrCode()
    {
        self::assertEquals([
            \Pengxul\Payf\Plugin\Unipay\QrCode\CancelPlugin::class,
        ], $this->plugin->getPlugins(['_action' => 'qr_code']));
    }

    public function testFoo()
    {
        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::SHORTCUT_MULTI_ACTION_ERROR);
        self::expectExceptionMessage('Cancel action [fooPlugins] not supported');

        $this->plugin->getPlugins(['_action' => 'foo']);
    }
}
