<?php

declare(strict_types=1);

namespace Pengxul\Payf\Tests\Plugin\Alipay\Shortcut;

use Pengxul\Payf\Exception\Exception;
use Pengxul\Payf\Exception\InvalidParamsException;
use Pengxul\Payf\Plugin\Alipay\Fund\TransCommonQueryPlugin;
use Pengxul\Payf\Plugin\Alipay\Shortcut\QueryShortcut;
use Pengxul\Payf\Plugin\Alipay\Trade\FastRefundQueryPlugin;
use Pengxul\Payf\Plugin\Alipay\Trade\QueryPlugin;
use Pengxul\Payf\Tests\TestCase;

class QueryShortcutTest extends TestCase
{
    protected $plugin;

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
            FastRefundQueryPlugin::class,
        ], $this->plugin->getPlugins(['_action' => 'refund']));
    }

    public function testTransfer()
    {
        self::assertEquals([
            TransCommonQueryPlugin::class,
        ], $this->plugin->getPlugins(['_action' => 'transfer']));
    }

    public function testFoo()
    {
        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::SHORTCUT_MULTI_ACTION_ERROR);
        self::expectExceptionMessage('Query action [fooPlugins] not supported');

        $this->plugin->getPlugins(['_action' => 'foo']);
    }
}
