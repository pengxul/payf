<?php

declare(strict_types=1);

namespace Pengxul\Payf\Tests\Plugin\Wechat\Shortcut;

use Pengxul\Payf\Exception\Exception;
use Pengxul\Payf\Exception\InvalidParamsException;
use Pengxul\Payf\Plugin\Wechat\Pay\Common\QueryRefundPlugin;
use Pengxul\Payf\Plugin\Wechat\Pay\Common\QueryPlugin;
use Pengxul\Payf\Plugin\Wechat\Shortcut\QueryShortcut;
use Pengxul\Payf\Tests\TestCase;

class QueryShortcutTest extends TestCase
{
    protected QueryShortcut $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new QueryShortcut();
    }

    public function testDefault()
    {
        self::assertEquals([
            QueryPlugin::class,
        ], $this->plugin->getPlugins([]));
    }

    public function testRefund()
    {
        self::assertEquals([
            QueryRefundPlugin::class,
        ], $this->plugin->getPlugins(['_action' => 'refund']));
    }

    public function testCombine()
    {
        self::assertEquals([
            \Pengxul\Payf\Plugin\Wechat\Pay\Combine\QueryPlugin::class,
        ], $this->plugin->getPlugins(['_action' => 'combine']));
    }

    public function testCombineParams()
    {
        self::assertEquals([
            \Pengxul\Payf\Plugin\Wechat\Pay\Combine\QueryPlugin::class,
        ], $this->plugin->getPlugins(['combine_out_trade_no' => '123abc']));
    }

    public function testFoo()
    {
        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::SHORTCUT_MULTI_ACTION_ERROR);
        self::expectExceptionMessage('Query action [fooPlugins] not supported');

        $this->plugin->getPlugins(['_action' => 'foo']);
    }
}
