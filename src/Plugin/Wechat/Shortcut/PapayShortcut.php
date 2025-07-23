<?php

declare(strict_types=1);

namespace Pengxul\Payf\Plugin\Wechat\Shortcut;

use Pengxul\Payf\Contract\ShortcutInterface;
use Pengxul\Payf\Exception\Exception;
use Pengxul\Payf\Exception\InvalidParamsException;
use Pengxul\Payf\Plugin\ParserPlugin;
use Pengxul\Payf\Plugin\Wechat\Papay\ApplyPlugin;
use Pengxul\Payf\Plugin\Wechat\Papay\ContractOrderPlugin;
use Pengxul\Payf\Plugin\Wechat\Papay\OnlyContractPlugin;
use Pengxul\Payf\Plugin\Wechat\Pay\Common\InvokePrepayV2Plugin;
use Pengxul\Payf\Plugin\Wechat\PreparePlugin;
use Pengxul\Payf\Plugin\Wechat\RadarSignPlugin;
use Yansongda\Supports\Str;

class PapayShortcut implements ShortcutInterface
{
    /**
     * @throws InvalidParamsException
     */
    public function getPlugins(array $params): array
    {
        $typeMethod = Str::camel($params['_action'] ?? 'default').'Plugins';

        if (method_exists($this, $typeMethod)) {
            return $this->{$typeMethod}($params);
        }

        throw new InvalidParamsException(Exception::SHORTCUT_MULTI_ACTION_ERROR, "Papay action [{$typeMethod}] not supported");
    }

    /**
     * 返回只签约（委托代扣）参数.
     *
     * @see https://pay.weixin.qq.com/wiki/doc/api/wxpay_v2/papay/chapter3_3.shtml
     */
    public function ContractPlugins(): array
    {
        return [
            PreparePlugin::class,
            OnlyContractPlugin::class,
        ];
    }

    /**
     * 申请代扣.
     *
     * @see https://pay.weixin.qq.com/wiki/doc/api/wxpay_v2/papay/chapter3_8.shtml
     */
    public function applyPlugins(): array
    {
        return [
            PreparePlugin::class,
            ApplyPlugin::class,
            RadarSignPlugin::class,
            ParserPlugin::class,
        ];
    }

    /**
     * 支付中签约.
     *
     * @see https://pay.weixin.qq.com/wiki/doc/api/wxpay_v2/papay/chapter3_5.shtml
     */
    protected function defaultPlugins(array $params): array
    {
        return [
            PreparePlugin::class,
            ContractOrderPlugin::class,
            RadarSignPlugin::class,
            $this->getInvoke($params),
            ParserPlugin::class,
        ];
    }

    protected function getInvoke(array $params): string
    {
        switch ($params['_type'] ?? 'default') {
            case 'app':
                return \Pengxul\Payf\Plugin\Wechat\Pay\App\InvokePrepayV2Plugin::class;

            case 'mini':
                return \Pengxul\Payf\Plugin\Wechat\Pay\Mini\InvokePrepayV2Plugin::class;

            default:
                return InvokePrepayV2Plugin::class;
        }
    }
}
