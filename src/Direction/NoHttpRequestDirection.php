<?php

declare(strict_types=1);

namespace Pengxul\Payf\Direction;

use Psr\Http\Message\ResponseInterface;
use Pengxul\Payf\Contract\DirectionInterface;
use Pengxul\Payf\Contract\PackerInterface;

class NoHttpRequestDirection implements DirectionInterface
{
    public function parse(PackerInterface $packer, ?ResponseInterface $response): ?ResponseInterface
    {
        return $response;
    }
}
