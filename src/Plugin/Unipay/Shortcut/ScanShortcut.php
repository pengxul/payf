<?php

declare(strict_types=1);

namespace Pengxul\Payf\Plugin\Unipay\Shortcut;

use Pengxul\Payf\Contract\ShortcutInterface;
use Pengxul\Payf\Exception\Exception;
use Pengxul\Payf\Exception\InvalidParamsException;
use Pengxul\Payf\Plugin\Unipay\QrCode\ScanFeePlugin;
use Pengxul\Payf\Plugin\Unipay\QrCode\ScanNormalPlugin;
use Pengxul\Payf\Plugin\Unipay\QrCode\ScanPreAuthPlugin;
use Pengxul\Payf\Plugin\Unipay\QrCode\ScanPreOrderPlugin;
use Yansongda\Supports\Str;

class ScanShortcut implements ShortcutInterface
{
    /**
     * @throws InvalidParamsException
     */
    public function getPlugins(array $params): array
    {
        $typeMethod = Str::camel($params['_action'] ?? 'default').'Plugins';

        if (method_exists($this, $typeMethod)) {
            return $this->{$typeMethod}();
        }

        throw new InvalidParamsException(Exception::SHORTCUT_MULTI_ACTION_ERROR, "Scan action [{$typeMethod}] not supported");
    }

    protected function defaultPlugins(): array
    {
        return [
            ScanNormalPlugin::class,
        ];
    }

    protected function preAuthPlugins(): array
    {
        return [
            ScanPreAuthPlugin::class,
        ];
    }

    protected function preOrderPlugins(): array
    {
        return [
            ScanPreOrderPlugin::class,
        ];
    }

    protected function feePlugins(): array
    {
        return [
            ScanFeePlugin::class,
        ];
    }
}
