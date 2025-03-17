<?php
namespace YOCLIB\DNS\Tests\Types;

use PHPUnit\Framework\TestCase;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSMnemonicException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Types\WKS;

class WKSTest extends TestCase{

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSMnemonicException
     * @throws DNSTypeException
     */
    public function testDeserializeFromPresentationFormat(){
        self::assertSame(6,WKS::deserializeFromPresentationFormat('127.0.0.1 6')->getFields()[1]->getValue());
        self::assertSame(6,WKS::deserializeFromPresentationFormat('127.0.0.1 TCP')->getFields()[1]->getValue());

        self::assertSame(17,WKS::deserializeFromPresentationFormat('127.0.0.1 17')->getFields()[1]->getValue());
        self::assertSame(17,WKS::deserializeFromPresentationFormat('127.0.0.1 UDP')->getFields()[1]->getValue());

        self::assertSame(6,WKS::deserializeFromPresentationFormat('127.0.0.1 6 1 2 3 4')->getFields()[1]->getValue());
        self::assertSame([1,2,3,4],WKS::deserializeFromPresentationFormat('127.0.0.1 6 1 2 3 4')->getFields()[2]->getValue());
        self::assertSame(6,WKS::deserializeFromPresentationFormat('127.0.0.1 TCP 1 2 3 4')->getFields()[1]->getValue());
        self::assertSame([1,2,3,4],WKS::deserializeFromPresentationFormat('127.0.0.1 TCP 1 2 3 4')->getFields()[2]->getValue());

        self::assertSame(17,WKS::deserializeFromPresentationFormat('127.0.0.1 17 1 2 3 4')->getFields()[1]->getValue());
        self::assertSame([1,2,3,4],WKS::deserializeFromPresentationFormat('127.0.0.1 17 1 2 3 4')->getFields()[2]->getValue());
        self::assertSame(17,WKS::deserializeFromPresentationFormat('127.0.0.1 UDP 1 2 3 4')->getFields()[1]->getValue());
        self::assertSame([1,2,3,4],WKS::deserializeFromPresentationFormat('127.0.0.1 UDP 1 2 3 4')->getFields()[2]->getValue());

        self::assertSame(6,WKS::deserializeFromPresentationFormat('127.0.0.1 6 SMTP')->getFields()[1]->getValue());
        self::assertSame([25],WKS::deserializeFromPresentationFormat('127.0.0.1 6 SMTP')->getFields()[2]->getValue());
        self::assertSame(6,WKS::deserializeFromPresentationFormat('127.0.0.1 TCP SMTP')->getFields()[1]->getValue());
        self::assertSame([25],WKS::deserializeFromPresentationFormat('127.0.0.1 TCP SMTP')->getFields()[2]->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSMnemonicException
     * @throws DNSTypeException
     */
    public function testDeserializeFromPresentationFormatUnknownProtocol(){
        self::expectException(DNSMnemonicException::class);
        self::expectExceptionMessage('Unknown mnemonic.');

        WKS::deserializeFromPresentationFormat('127.0.0.1 NON-EXISTING');
    }

}