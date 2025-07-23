<?php

declare(strict_types=1);

namespace Pengxul\Payf\Plugin\Unipay\Shortcut;

use Pengxul\Payf\Contract\ShortcutInterface;
use Pengxul\Payf\Exception\Exception;
use Pengxul\Payf\Exception\InvalidParamsException;
use Pengxul\Payf\Plugin\Unipay\OnlineGateway\CancelPlugin;
use Yansongda\Supports\Str;

class CancelShortcut implements ShortcutInterface
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

        throw new InvalidParamsException(Exception::SHORTCUT_MULTI_ACTION_ERROR, "Cancel action [{$typeMethod}] not supported");
    }

    protected function defaultPlugins(): array
    {
        return [
            CancelPlugin::class,
        ];
    }

    protected function qrCodePlugins(): array
    {
        return [
            \Pengxul\Payf\Plugin\Unipay\QrCode\CancelPlugin::class,
        ];
    }
}
