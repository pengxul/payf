<?php

namespace Pengxul\Payf\Tests\Direction;

use GuzzleHttp\Psr7\Response;
use Pengxul\Payf\Packer\JsonPacker;
use Pengxul\Payf\Direction\CollectionDirection;
use Pengxul\Payf\Pay;
use Pengxul\Payf\Tests\TestCase;

class CollectionDirectionTest extends TestCase
{
    protected CollectionDirection $parser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->parser = new CollectionDirection();
    }

    public function testNormal()
    {
        Pay::config();

        $response = new Response(200, [], '{"name": "yansongda"}');

        $result = $this->parser->parse(new JsonPacker(), $response);

        self::assertEquals(['name' => 'yansongda'], $result->all());
    }
}
