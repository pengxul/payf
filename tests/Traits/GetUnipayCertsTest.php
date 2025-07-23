<?php

namespace Pengxul\Payf\Tests\Traits;

use Pengxul\Payf\Contract\ConfigInterface;
use Pengxul\Payf\Exception\Exception;
use Pengxul\Payf\Exception\InvalidConfigException;
use Pengxul\Payf\Pay;
use Pengxul\Payf\Tests\Stubs\Traits\GetUnipayCertsStub;
use Pengxul\Payf\Tests\TestCase;
use function Pengxul\Payf\get_unipay_config;

class GetUnipayCertsTest extends TestCase
{
    /**
     * @var GetUnipayCertsStub
     */
    protected $trait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->trait = new GetUnipayCertsStub();
    }

    public function testNormal()
    {
        $certId = $this->trait->getCertId('default', get_unipay_config([]));

        $config = get_unipay_config([]);

        self::assertEquals('69903319369', $certId);
        self::assertArrayHasKey('cert', $config['certs']);
        self::assertArrayHasKey('pkey', $config['certs']);
    }

    public function testMissingCert()
    {
        $config = Pay::get(ConfigInterface::class);
        $config->set('unipay.default.mch_cert_path', null);

        $this->expectException(InvalidConfigException::class);
        self::expectExceptionCode(Exception::UNIPAY_CONFIG_ERROR);
        $this->expectExceptionMessage('Missing Unipay Config -- [mch_cert_path] or [mch_cert_password]');

        $this->trait->getCertId('default', get_unipay_config([]));
    }

    public function testMissingCertPassword()
    {
        $config = Pay::get(ConfigInterface::class);
        $config->set('unipay.default.mch_cert_password', null);

        $this->expectException(InvalidConfigException::class);
        self::expectExceptionCode(Exception::UNIPAY_CONFIG_ERROR);
        $this->expectExceptionMessage('Missing Unipay Config -- [mch_cert_path] or [mch_cert_password]');

        $this->trait->getCertId('default', get_unipay_config([]));
    }

    public function testWrongCert()
    {
        $config = Pay::get(ConfigInterface::class);

        $config->set('unipay.default.mch_cert_path', __DIR__.'/../Cert/foo');

        self::expectException(InvalidConfigException::class);
        self::expectExceptionCode(Exception::UNIPAY_CONFIG_ERROR);
        self::expectExceptionMessage('Read `mch_cert_path` Error');

        $this->trait->getCertId('default', get_unipay_config([]));
    }

    public function testNormalCached()
    {
        $certId = $this->trait->getCertId('default', get_unipay_config([]));

        $config = get_unipay_config([]);

        self::assertEquals('69903319369', $certId);
        self::assertArrayHasKey('cert', $config['certs']);
        self::assertArrayHasKey('pkey', $config['certs']);

        Pay::get(ConfigInterface::class)->set('unipay.default.mch_cert_path', null);

        $this->trait->getCertId('default', get_unipay_config([]));

        self::assertTrue(true);
    }
}
