<?php

declare(strict_types=1);

namespace Pengxul\Payf\Direction;

use Psr\Http\Message\ResponseInterface;
use Pengxul\Payf\Contract\DirectionInterface;
use Pengxul\Payf\Contract\PackerInterface;
use Pengxul\Payf\Exception\ContainerException;
use Pengxul\Payf\Exception\ServiceNotFoundException;
use Pengxul\Payf\Pay;
use Yansongda\Supports\Collection;

class CollectionDirection implements DirectionInterface
{
    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    public function parse(PackerInterface $packer, ?ResponseInterface $response): Collection
    {
        return new Collection(
            Pay::get(ArrayDirection::class)->parse($packer, $response)
        );
    }
}
