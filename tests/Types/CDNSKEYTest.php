<?php
namespace YOCLIB\DNS\Tests\Types;

use PHPUnit\Framework\TestCase;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSMnemonicException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Binary;
use YOCLIB\DNS\Fields\Bitmap;
use YOCLIB\DNS\Fields\UnsignedInteger16;
use YOCLIB\DNS\Fields\UnsignedInteger32;
use YOCLIB\DNS\Fields\UnsignedInteger8;
use YOCLIB\DNS\Types\CDNSKEY;

class CDNSKEYTest extends TestCase{

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public function testConstructor(): void{
        self::assertInstanceOf(CDNSKEY::class,new CDNSKEY([
            new UnsignedInteger16(1),
            new UnsignedInteger8(1),
            new UnsignedInteger8(1),
            new Binary(hex2bin('74657374')),
        ]));
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public function testConstructorTooLessFields(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('Only four fields allowed.');

        new CDNSKEY([
            new UnsignedInteger16(1),
            new UnsignedInteger8(1),
            new UnsignedInteger8(1),
        ]);
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testConstructorInvalidFirstField(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('First field should be an UInt16.');

        new CDNSKEY([
            new UnsignedInteger32(1),
            new UnsignedInteger8(1),
            new UnsignedInteger8(1),
            new Binary(hex2bin('74657374')),
        ]);
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testConstructorInvalidSecondField(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('Second field should be an UInt8.');

        new CDNSKEY([
            new UnsignedInteger16(1),
            new UnsignedInteger16(1),
            new UnsignedInteger8(1),
            new Binary(hex2bin('74657374')),
        ]);
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testConstructorInvalidThirdField(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('Third field should be an UInt8.');

        new CDNSKEY([
            new UnsignedInteger16(1),
            new UnsignedInteger8(1),
            new UnsignedInteger16(1),
            new Binary(hex2bin('74657374')),
        ]);
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testConstructorInvalidFourthField(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('Fourth field should be binary.');

        new CDNSKEY([
            new UnsignedInteger16(1),
            new UnsignedInteger8(1),
            new UnsignedInteger8(1),
            new Bitmap([]),
        ]);
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSMnemonicException
     * @throws DNSTypeException
     */
    public function testSerializeToPresentationFormat(): void{
//        self::assertSame('1 1 1 dGVzdA==',(new CDNSKEY([
//            new UnsignedInteger16(1),
//            new UnsignedInteger8(1),
//            new UnsignedInteger8(1),
//            new Binary(hex2bin('74657374')),
//        ]))->serializeToPresentationFormat());
        self::assertSame('1 1 RSAMD5 dGVzdA==',(new CDNSKEY([
            new UnsignedInteger16(1),
            new UnsignedInteger8(1),
            new UnsignedInteger8(1),
            new Binary(hex2bin('74657374')),
        ]))->serializeToPresentationFormat());
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSMnemonicException
     * @throws DNSTypeException
     */
    public function testDeserializeFromPresentationFormat(): void{
        self::assertSame(1,CDNSKEY::deserializeFromPresentationFormat('1 1 1 dGVzdA==')->getFields()[2]->getValue());
        self::assertSame(1,CDNSKEY::deserializeFromPresentationFormat('1 1 RSAMD5 dGVzdA==')->getFields()[2]->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSMnemonicException
     * @throws DNSTypeException
     */
    public function testDeserializeFromPresentationFormatTooLessFields(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('CDNSKEY record should contain at least 3 fields.');

        CDNSKEY::deserializeFromPresentationFormat('');
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public function testDeserializeFromPresentationFormatUnknownProtocol(): void{
        self::expectException(DNSMnemonicException::class);
        self::expectExceptionMessage('Invalid mnemonic key during deserialization.');

        CDNSKEY::deserializeFromPresentationFormat('1 1 NON-EXISTING dGVzdA==');
    }

}