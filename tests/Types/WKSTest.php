<?php
namespace YOCLIB\DNS\Tests\Types;

use PHPUnit\Framework\TestCase;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSMnemonicException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Binary;
use YOCLIB\DNS\Fields\Bitmap;
use YOCLIB\DNS\Fields\IPv4Address;
use YOCLIB\DNS\Fields\IPv6Address;
use YOCLIB\DNS\Fields\UnsignedInteger16;
use YOCLIB\DNS\Fields\UnsignedInteger8;
use YOCLIB\DNS\Types\WKS;

class WKSTest extends TestCase{

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public function testConstructor(): void{
        self::assertInstanceOf(WKS::class,new WKS([
            new IPv4Address('127.0.0.1'),
            new UnsignedInteger8(6),
            new Bitmap([25]),
        ]));
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public function testConstructorTooLessFields(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('Only three fields allowed.');

        new WKS([
            new IPv4Address('127.0.0.1'),
            new UnsignedInteger8(6),
        ]);
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public function testConstructorInvalidFirstField(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('First field should be an IPv4 address.');

        new WKS([
            new IPv6Address('::'),
            new UnsignedInteger8(6),
            new Bitmap([25]),
        ]);
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public function testConstructorInvalidSecondField(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('Second field should be an UInt8.');

        new WKS([
            new IPv4Address('127.0.0.1'),
            new UnsignedInteger16(6),
            new Bitmap([25]),
        ]);
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public function testConstructorInvalidThirdField(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('Third field should be a bitmap.');

        new WKS([
            new IPv4Address('127.0.0.1'),
            new UnsignedInteger8(6),
            new Binary(''),
        ]);
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSMnemonicException
     * @throws DNSTypeException
     */
    public function testSerializeToPresentationFormat(): void{
        self::assertSame('127.0.0.1 6',(new WKS([
            new IPv4Address('127.0.0.1'),
            new UnsignedInteger8(6),
            new Bitmap([]),
        ]))->serializeToPresentationFormat());
//        self::assertSame('127.0.0.1 TCP',(new WKS([
//            new IPv4Address('127.0.0.1'),
//            new UnsignedInteger8(6),
//            new Bitmap([]),
//        ]))->serializeToPresentationFormat());

        self::assertSame('127.0.0.1 17',(new WKS([
            new IPv4Address('127.0.0.1'),
            new UnsignedInteger8(17),
            new Bitmap([]),
        ]))->serializeToPresentationFormat());
//        self::assertSame('127.0.0.1 UDP',(new WKS([
//            new IPv4Address('127.0.0.1'),
//            new UnsignedInteger8(17),
//            new Bitmap([]),
//        ]))->serializeToPresentationFormat());

        self::assertSame('127.0.0.1 6 1 2 3 4',(new WKS([
            new IPv4Address('127.0.0.1'),
            new UnsignedInteger8(6),
            new Bitmap([1,2,3,4]),
        ]))->serializeToPresentationFormat());
//        self::assertSame('127.0.0.1 TCP 1 2 3 4',(new WKS([
//            new IPv4Address('127.0.0.1'),
//            new UnsignedInteger8(6),
//            new Bitmap([1,2,3,4]),
//        ]))->serializeToPresentationFormat());

        self::assertSame('127.0.0.1 17 1 2 3 4',(new WKS([
            new IPv4Address('127.0.0.1'),
            new UnsignedInteger8(17),
            new Bitmap([1,2,3,4]),
        ]))->serializeToPresentationFormat());
//        self::assertSame('127.0.0.1 UDP 1 2 3 4',(new WKS([
//            new IPv4Address('127.0.0.1'),
//            new UnsignedInteger8(17),
//            new Bitmap([1,2,3,4]),
//        ]))->serializeToPresentationFormat());

        self::assertSame('127.0.0.1 6 SMTP',(new WKS([
            new IPv4Address('127.0.0.1'),
            new UnsignedInteger8(6),
            new Bitmap([25]),
        ]))->serializeToPresentationFormat());
//        self::assertSame('127.0.0.1 TCP SMTP',(new WKS([
//            new IPv4Address('127.0.0.1'),
//            new UnsignedInteger8(6),
//            new Bitmap([25]),
//        ]))->serializeToPresentationFormat());
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSMnemonicException
     * @throws DNSTypeException
     */
    public function testDeserializeFromPresentationFormat(): void{
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
    public function testDeserializeFromPresentationFormatTooLessFields(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('WKS record should contain at least 2 fields.');

        WKS::deserializeFromPresentationFormat('127.0.0.1');
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSMnemonicException
     * @throws DNSTypeException
     */
    public function testDeserializeFromPresentationFormatUnknownProtocol(): void{
        self::expectException(DNSMnemonicException::class);
        self::expectExceptionMessage('Invalid mnemonic key during deserialization.');

        WKS::deserializeFromPresentationFormat('127.0.0.1 NON-EXISTING');
    }

}