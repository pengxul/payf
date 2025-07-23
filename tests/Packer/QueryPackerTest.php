<?php

namespace Pengxul\Payf\Tests\Packer;

use Pengxul\Payf\Packer\QueryPacker;

class QueryPackerTest extends \Pengxul\Payf\Tests\TestCase
{
    protected QueryPacker $packer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->packer = new QueryPacker();
    }

    public function testPack()
    {
        $array = ['name' => 'yansongda', 'age' => '29'];
        $str = 'name=yansongda&age=29';

        self::assertEquals($str, $this->packer->pack($array));
    }

    public function testUnpack()
    {
        $array = ['name' => 'yansongda', 'age' => '29'];
        $str = 'name=yansongda&age=29';

        self::assertEquals($array, $this->packer->unpack($str));
    }
}
