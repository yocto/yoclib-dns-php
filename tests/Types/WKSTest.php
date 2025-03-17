<?php
namespace YOCLIB\DNS\Tests\Types;

use PHPUnit\Framework\TestCase;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Types\WKS;

class WKSTest extends TestCase{

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public function testDeserializeFromPresentationFormat(){
        self::assertSame(6,WKS::deserializeFromPresentationFormat('127.0.0.1 6')->getFields()[1]->getValue());
        self::assertSame(6,WKS::deserializeFromPresentationFormat('127.0.0.1 TCP')->getFields()[1]->getValue());

        self::assertSame(17,WKS::deserializeFromPresentationFormat('127.0.0.1 17')->getFields()[1]->getValue());
        self::assertSame(17,WKS::deserializeFromPresentationFormat('127.0.0.1 UDP')->getFields()[1]->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public function testDeserializeFromPresentationFormatUnknownProtocol(){
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('Unknown protocol mnemonic.');

        WKS::deserializeFromPresentationFormat('127.0.0.1 NON-EXISTING');
    }

}