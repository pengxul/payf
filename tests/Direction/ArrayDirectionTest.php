<?php

namespace Pengxul\Payf\Tests\Direction;

use GuzzleHttp\Psr7\Response;
use Pengxul\Payf\Exception\Exception;
use Pengxul\Payf\Exception\InvalidResponseException;
use Pengxul\Payf\Packer\JsonPacker;
use Pengxul\Payf\Packer\QueryPacker;
use Pengxul\Payf\Direction\ArrayDirection;
use Pengxul\Payf\Tests\TestCase;

class ArrayDirectionTest extends TestCase
{
    protected ArrayDirection $parser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->parser = new ArrayDirection();
    }

    public function testResponseNull()
    {
        self::expectException(InvalidResponseException::class);
        self::expectExceptionCode(Exception::RESPONSE_NONE);

        $this->parser->parse(new JsonPacker(), null);
    }

    public function testWrongFormat()
    {
        self::expectException(InvalidResponseException::class);
        self::expectExceptionCode(Exception::UNPACK_RESPONSE_ERROR);

        $response = new Response(200, [], '{"name": "yansongda"}a');

        $this->parser->parse(new JsonPacker(), $response);
    }

    public function testNormal()
    {
        $response = new Response(200, [], '{"name": "yansongda"}');

        $result = $this->parser->parse(new JsonPacker(), $response);

        self::assertEquals(['name' => 'yansongda'], $result);
    }

    public function testReadContents()
    {
        $response = new Response(200, [], '{"name": "yansongda"}');

        $response->getBody()->read(2);

        $result = $this->parser->parse(new JsonPacker(), $response);

        self::assertEquals(['name' => 'yansongda'], $result);
    }

    public function testQueryBody()
    {
        $response = new Response(200, [], 'name=yansongda&age=29');

        $result = $this->parser->parse(new QueryPacker(), $response);

        self::assertEqualsCanonicalizing(['name' => 'yansongda', 'age' => '29'], $result);
    }

    public function testJsonWith()
    {
        $url = 'https://yansongda.cn?name=yansongda&age=29';

        $response = new Response(200, [], json_encode(['h5_url' => $url]));

        $result = $this->parser->parse(new JsonPacker(), $response);

        self::assertEquals('https://yansongda.cn?name=yansongda&age=29', $result['h5_url']);
    }
}
