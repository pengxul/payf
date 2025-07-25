<?php

declare(strict_types=1);

namespace Pengxul\Payf\Tests\Plugin\Wechat\Shortcut;

use Pengxul\Payf\Exception\Exception;
use Pengxul\Payf\Exception\InvalidParamsException;
use Pengxul\Payf\Plugin\Alipay\Fund\TransCommonQueryPlugin;
use Pengxul\Payf\Plugin\Wechat\Pay\Common\ClosePlugin;
use Pengxul\Payf\Plugin\Wechat\Shortcut\CloseShortcut;
use Pengxul\Payf\Tests\TestCase;

class CloseShortcutTest extends TestCase
{
    protected CloseShortcut $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new CloseShortcut();
    }

    public function testDefault()
    {
        self::assertEquals([
            ClosePlugin::class,
        ], $this->plugin->getPlugins([]));
    }

    public function testCombine()
    {
        self::assertEquals([
            \Pengxul\Payf\Plugin\Wechat\Pay\Combine\ClosePlugin::class,
        ], $this->plugin->getPlugins(['_action' => 'combine']));
    }

    public function testCombineParams()
    {
        self::assertEquals([
            \Pengxul\Payf\Plugin\Wechat\Pay\Combine\ClosePlugin::class,
        ], $this->plugin->getPlugins(['combine_out_trade_no' => '123abc']));

        self::assertEquals([
            \Pengxul\Payf\Plugin\Wechat\Pay\Combine\ClosePlugin::class,
        ], $this->plugin->getPlugins(['sub_orders' => '123abc']));
    }

    public function testFoo()
    {
        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::SHORTCUT_MULTI_ACTION_ERROR);
        self::expectExceptionMessage('Query action [fooPlugins] not supported');

        $this->plugin->getPlugins(['_action' => 'foo']);
    }
}
