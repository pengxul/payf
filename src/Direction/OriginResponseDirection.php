<?php

declare(strict_types=1);

namespace Pengxul\Payf\Direction;

use Psr\Http\Message\ResponseInterface;
use Pengxul\Payf\Contract\DirectionInterface;
use Pengxul\Payf\Contract\PackerInterface;
use Pengxul\Payf\Exception\Exception;
use Pengxul\Payf\Exception\InvalidResponseException;

class OriginResponseDirection implements DirectionInterface
{
    /**
     * @throws InvalidResponseException
     */
    public function parse(PackerInterface $packer, ?ResponseInterface $response): ?ResponseInterface
    {
        if (!is_null($response)) {
            return $response;
        }

        throw new InvalidResponseException(Exception::INVALID_RESPONSE_CODE);
    }
}
