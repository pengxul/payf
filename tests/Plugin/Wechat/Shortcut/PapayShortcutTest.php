<?php

declare(strict_types=1);

namespace Pengxul\Payf\Tests\Plugin\Wechat\Shortcut;

use Pengxul\Payf\Exception\Exception;
use Pengxul\Payf\Exception\InvalidParamsException;
use Pengxul\Payf\Plugin\ParserPlugin;
use Pengxul\Payf\Plugin\Wechat\Papay\ApplyPlugin;
use Pengxul\Payf\Plugin\Wechat\Papay\ContractOrderPlugin;
use Pengxul\Payf\Plugin\Wechat\Papay\OnlyContractPlugin;
use Pengxul\Payf\Plugin\Wechat\Pay\Common\InvokePrepayV2Plugin;
use Pengxul\Payf\Plugin\Wechat\PreparePlugin;
use Pengxul\Payf\Plugin\Wechat\RadarSignPlugin;
use Pengxul\Payf\Plugin\Wechat\Shortcut\PapayShortcut;
use Pengxul\Payf\Tests\TestCase;

class PapayShortcutTest extends TestCase
{
    protected PapayShortcut $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new PapayShortcut();
    }

    public function testDefault()
    {
        self::assertEquals([
            PreparePlugin::class,
            ContractOrderPlugin::class,
            RadarSignPlugin::class,
            InvokePrepayV2Plugin::class,
            ParserPlugin::class,
        ], $this->plugin->getPlugins([]));
    }

    public function testDefaultMini()
    {
        self::assertEquals([
            PreparePlugin::class,
            ContractOrderPlugin::class,
            RadarSignPlugin::class,
            \Pengxul\Payf\Plugin\Wechat\Pay\Mini\InvokePrepayV2Plugin::class,
            ParserPlugin::class,
        ], $this->plugin->getPlugins(['_type' => 'mini']));
    }

    public function testDefaultApp()
    {
        self::assertEquals([
            PreparePlugin::class,
            ContractOrderPlugin::class,
            RadarSignPlugin::class,
            \Pengxul\Payf\Plugin\Wechat\Pay\App\InvokePrepayV2Plugin::class,
            ParserPlugin::class,
        ], $this->plugin->getPlugins(['_type' => 'app']));
    }

    public function testContract()
    {
        self::assertEquals([
            PreparePlugin::class,
            OnlyContractPlugin::class,
        ], $this->plugin->getPlugins(['_action' => 'contract']));
    }

    public function testApply()
    {
        self::assertEquals([
            PreparePlugin::class,
            ApplyPlugin::class,
            RadarSignPlugin::class,
            ParserPlugin::class,
        ], $this->plugin->getPlugins(['_action' => 'apply']));
    }

    public function testFoo()
    {
        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::SHORTCUT_MULTI_ACTION_ERROR);
        self::expectExceptionMessage('Papay action [fooPlugins] not supported');

        $this->plugin->getPlugins(['_action' => 'foo']);
    }
}
