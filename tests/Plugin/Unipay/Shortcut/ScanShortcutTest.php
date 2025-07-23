<?php

declare(strict_types=1);

namespace Pengxul\Payf\Tests\Plugin\Unipay\Shortcut;

use Pengxul\Payf\Exception\Exception;
use Pengxul\Payf\Exception\InvalidParamsException;
use Pengxul\Payf\Plugin\Unipay\QrCode\ScanFeePlugin;
use Pengxul\Payf\Plugin\Unipay\QrCode\ScanNormalPlugin;
use Pengxul\Payf\Plugin\Unipay\QrCode\ScanPreAuthPlugin;
use Pengxul\Payf\Plugin\Unipay\QrCode\ScanPreOrderPlugin;
use Pengxul\Payf\Plugin\Unipay\Shortcut\ScanShortcut;
use Pengxul\Payf\Tests\TestCase;

class ScanShortcutTest extends TestCase
{
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new ScanShortcut();
    }

    public function testDefault()
    {
        self::assertEquals([
            ScanNormalPlugin::class,
        ], $this->plugin->getPlugins([]));
    }

    public function testPreAuth()
    {
        self::assertEquals([
            ScanPreAuthPlugin::class,
        ], $this->plugin->getPlugins(['_action' => 'pre_auth']));
    }

    public function testPreOrder()
    {
        self::assertEquals([
            ScanPreOrderPlugin::class,
        ], $this->plugin->getPlugins(['_action' => 'pre_order']));
    }

    public function testFee()
    {
        self::assertEquals([
            ScanFeePlugin::class,
        ], $this->plugin->getPlugins(['_action' => 'fee']));
    }

    public function testFoo()
    {
        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::SHORTCUT_MULTI_ACTION_ERROR);
        self::expectExceptionMessage('Scan action [fooPlugins] not supported');

        $this->plugin->getPlugins(['_action' => 'foo']);
    }
}
