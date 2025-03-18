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
use YOCLIB\DNS\Types\CERT;

class CERTTest extends TestCase{

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public function testConstructor(): void{
        self::assertInstanceOf(CERT::class,new CERT([
            new UnsignedInteger16(3),
            new UnsignedInteger16(0),
            new UnsignedInteger8(0),
            new Binary(hex2bin('AABBCCDD')),
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

        new CERT([
            new UnsignedInteger16(3),
            new UnsignedInteger16(0),
            new UnsignedInteger8(0),
        ]);
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testConstructorInvalidFirstField(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('First field should be an UInt16.');

        new CERT([
            new UnsignedInteger32(3),
            new UnsignedInteger16(0),
            new UnsignedInteger8(0),
            new Binary(hex2bin('AABBCCDD')),
        ]);
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testConstructorInvalidSecondField(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('Second field should be an UInt16.');

        new CERT([
            new UnsignedInteger16(3),
            new UnsignedInteger32(0),
            new UnsignedInteger8(0),
            new Binary(hex2bin('AABBCCDD')),
        ]);
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testConstructorInvalidThirdField(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('Third field should be an UInt8.');

        new CERT([
            new UnsignedInteger16(3),
            new UnsignedInteger16(0),
            new UnsignedInteger16(0),
            new Binary(hex2bin('AABBCCDD')),
        ]);
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testConstructorInvalidFourthField(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('Fourth field should be binary.');

        new CERT([
            new UnsignedInteger16(3),
            new UnsignedInteger16(0),
            new UnsignedInteger8(0),
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
//        self::assertSame('3 0 0 qrvM3Q==',(new CERT([
//            new UnsignedInteger16(3),
//            new UnsignedInteger16(0),
//            new UnsignedInteger8(0),
//            new Binary(hex2bin('AABBCCDD')),
//        ]))->serializeToPresentationFormat());
        self::assertSame('PGP 0 0 qrvM3Q==',(new CERT([
            new UnsignedInteger16(3),
            new UnsignedInteger16(0),
            new UnsignedInteger8(0),
            new Binary(hex2bin('AABBCCDD')),
        ]))->serializeToPresentationFormat());
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSMnemonicException
     * @throws DNSTypeException
     */
    public function testDeserializeFromPresentationFormat(): void{
        self::assertSame(3,CERT::deserializeFromPresentationFormat('3 0 0 qrvM3Q==')->getFields()[0]->getValue());
        self::assertSame(3,CERT::deserializeFromPresentationFormat('PGP 0 0 qrvM3Q==')->getFields()[0]->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSMnemonicException
     * @throws DNSTypeException
     */
    public function testDeserializeFromPresentationFormatTooLessFields(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('CERT record should contain at least 3 fields.');

        CERT::deserializeFromPresentationFormat('');
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public function testDeserializeFromPresentationFormatUnknownProtocol(): void{
        self::expectException(DNSMnemonicException::class);
        self::expectExceptionMessage('Invalid mnemonic key during deserialization.');

        CERT::deserializeFromPresentationFormat('NON-EXISTING 0 0 qrvM3Q==');
    }

}