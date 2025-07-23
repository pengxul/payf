<?php

namespace Pengxul\Payf\Tests\Direction;

use Pengxul\Payf\Exception\Exception;
use Pengxul\Payf\Exception\InvalidResponseException;
use Pengxul\Payf\Packer\JsonPacker;
use Pengxul\Payf\Direction\OriginResponseDirection;
use Pengxul\Payf\Tests\TestCase;

class OriginResponseDirectionTest extends TestCase
{
    protected OriginResponseDirection $parser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->parser = new OriginResponseDirection();
    }

    public function testResponseNull()
    {
        self::expectException(InvalidResponseException::class);
        self::expectExceptionCode(Exception::INVALID_RESPONSE_CODE);

        $this->parser->parse(new JsonPacker(), null);
    }
}
