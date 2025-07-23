<?php

declare(strict_types=1);

namespace Pengxul\Payf\Tests\Plugin\Unipay\Shortcut;

use Pengxul\Payf\Exception\Exception;
use Pengxul\Payf\Exception\InvalidParamsException;
use Pengxul\Payf\Plugin\Unipay\QrCode\PosNormalPlugin;
use Pengxul\Payf\Plugin\Unipay\QrCode\PosPreAuthPlugin;
use Pengxul\Payf\Plugin\Unipay\Shortcut\PosShortcut;
use Pengxul\Payf\Tests\TestCase;

class PosShortcutTest extends TestCase
{
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new PosShortcut();
    }

    public function testDefault()
    {
        self::assertEquals([
            PosNormalPlugin::class,
        ], $this->plugin->getPlugins([]));
    }

    public function testPreAuth()
    {
        self::assertEquals([
            PosPreAuthPlugin::class,
        ], $this->plugin->getPlugins(['_action' => 'pre_auth']));
    }

    public function testFoo()
    {
        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::SHORTCUT_MULTI_ACTION_ERROR);
        self::expectExceptionMessage('Pos action [fooPlugins] not supported');

        $this->plugin->getPlugins(['_action' => 'foo']);
    }
}
