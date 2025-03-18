<?php
namespace YOCLIB\DNS\Tests\Types;

use PHPUnit\Framework\TestCase;

use YOCLIB\DNS\DNSType;
use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSMnemonicException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Binary;
use YOCLIB\DNS\Fields\Bitmap;
use YOCLIB\DNS\Fields\FQDN;
use YOCLIB\DNS\Fields\IPv4Address;
use YOCLIB\DNS\Fields\UnsignedInteger16;
use YOCLIB\DNS\Fields\UnsignedInteger32;
use YOCLIB\DNS\Fields\UnsignedInteger64;
use YOCLIB\DNS\Fields\UnsignedInteger8;
use YOCLIB\DNS\Types\RRSIG;

class RRSIGTest extends TestCase{

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public function testConstructor(): void{
        self::assertInstanceOf(RRSIG::class,new RRSIG([
            new UnsignedInteger16(DNSType::A),
            new UnsignedInteger8(5),
            new UnsignedInteger8(3),
            new UnsignedInteger32(86400),
            new UnsignedInteger32(4660),
            new UnsignedInteger32(4660),
            new UnsignedInteger16(2642),
            new FQDN('example','com',''),
            new Binary(hex2bin('A090755BA58D1AFFA576F4375831B4310920E481218D18A9F164EB3D81AFD3B875D3C75428631E0CF2A28D50875F70C329D7DBFAFEA807DC1FBA1DC34C95D401F23F334CE63BFCF3F1B5B44739E5F0EDED18D6B33F040A911376D173D757A9F0C1FA1798941BB0B36B2DF9062790FA7F0166F2737EEA907378341FB12DC0A77A')),
        ]));
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public function testConstructorTooLessFields(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('Only nine fields allowed.');

        new RRSIG([
            new UnsignedInteger16(DNSType::A),
            new UnsignedInteger8(5),
            new UnsignedInteger8(3),
            new UnsignedInteger32(86400),
            new UnsignedInteger32(4660),
            new UnsignedInteger32(4660),
            new UnsignedInteger16(2642),
            new FQDN('example','com',''),
        ]);
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testConstructorInvalidFirstField(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('First field should be an UInt16.');

        new RRSIG([
            new UnsignedInteger8(DNSType::A),
            new UnsignedInteger8(5),
            new UnsignedInteger8(3),
            new UnsignedInteger32(86400),
            new UnsignedInteger32(4660),
            new UnsignedInteger32(4660),
            new UnsignedInteger16(2642),
            new FQDN('example','com',''),
            new Binary(hex2bin('AABBCCDD')),
        ]);
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testConstructorInvalidSecondField(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('Second field should be an UInt8.');

        new RRSIG([
            new UnsignedInteger16(DNSType::A),
            new UnsignedInteger16(5),
            new UnsignedInteger8(3),
            new UnsignedInteger32(86400),
            new UnsignedInteger32(4660),
            new UnsignedInteger32(4660),
            new UnsignedInteger16(2642),
            new FQDN('example','com',''),
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

        new RRSIG([
            new UnsignedInteger16(DNSType::A),
            new UnsignedInteger8(5),
            new UnsignedInteger16(3),
            new UnsignedInteger32(86400),
            new UnsignedInteger32(4660),
            new UnsignedInteger32(4660),
            new UnsignedInteger16(2642),
            new FQDN('example','com',''),
            new Binary(hex2bin('AABBCCDD')),
        ]);
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testConstructorInvalidFourthField(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('Fourth field should be an UInt32 or an empty binary.');

        new RRSIG([
            new UnsignedInteger16(DNSType::A),
            new UnsignedInteger8(5),
            new UnsignedInteger8(3),
            new UnsignedInteger64(86400),
            new UnsignedInteger32(4660),
            new UnsignedInteger32(4660),
            new UnsignedInteger16(2642),
            new FQDN('example','com',''),
            new Binary(hex2bin('AABBCCDD')),
        ]);
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testConstructorInvalidFifthField(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('Fifth field should be an UInt32.');

        new RRSIG([
            new UnsignedInteger16(DNSType::A),
            new UnsignedInteger8(5),
            new UnsignedInteger8(3),
            new UnsignedInteger32(86400),
            new UnsignedInteger64(4660),
            new UnsignedInteger32(4660),
            new UnsignedInteger16(2642),
            new FQDN('example','com',''),
            new Binary(hex2bin('AABBCCDD')),
        ]);
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testConstructorInvalidSixthField(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('Sixth field should be an UInt32.');

        new RRSIG([
            new UnsignedInteger16(DNSType::A),
            new UnsignedInteger8(5),
            new UnsignedInteger8(3),
            new UnsignedInteger32(86400),
            new UnsignedInteger32(4660),
            new UnsignedInteger64(4660),
            new UnsignedInteger16(2642),
            new FQDN('example','com',''),
            new Binary(hex2bin('AABBCCDD')),
        ]);
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testConstructorInvalidSeventhField(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('Seventh field should be an UInt16.');

        new RRSIG([
            new UnsignedInteger16(DNSType::A),
            new UnsignedInteger8(5),
            new UnsignedInteger8(3),
            new UnsignedInteger32(86400),
            new UnsignedInteger32(4660),
            new UnsignedInteger32(4660),
            new UnsignedInteger32(2642),
            new FQDN('example','com',''),
            new Binary(hex2bin('AABBCCDD')),
        ]);
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testConstructorInvalidEighthField(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('Eighth field should be a FQDN.');

        new RRSIG([
            new UnsignedInteger16(DNSType::A),
            new UnsignedInteger8(5),
            new UnsignedInteger8(3),
            new UnsignedInteger32(86400),
            new UnsignedInteger32(4660),
            new UnsignedInteger32(4660),
            new UnsignedInteger16(2642),
            new IPv4Address('127.0.0.1'),
            new Binary(hex2bin('AABBCCDD')),
        ]);
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testConstructorInvalidNinthField(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('Ninth field should be binary.');

        new RRSIG([
            new UnsignedInteger16(DNSType::A),
            new UnsignedInteger8(5),
            new UnsignedInteger8(3),
            new UnsignedInteger32(86400),
            new UnsignedInteger32(4660),
            new UnsignedInteger32(4660),
            new UnsignedInteger16(2642),
            new FQDN('example','com',''),
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
        self::assertSame('A 5 3 86400 20030322173103 20030220173103 2642 example.com. qrvM3Q==',(new RRSIG([
            new UnsignedInteger16(DNSType::A),
            new UnsignedInteger8(5),
            new UnsignedInteger8(3),
            new UnsignedInteger32(86400),
            new UnsignedInteger32(1048354263),
            new UnsignedInteger32(1045762263),
            new UnsignedInteger16(2642),
            new FQDN('example','com',''),
            new Binary(hex2bin('AABBCCDD')),
        ]))->serializeToPresentationFormat());

        self::assertSame('TYPE1234 5 3 86400 20030322173103 20030220173103 2642 example.com. qrvM3Q==',(new RRSIG([
            new UnsignedInteger16(1234),
            new UnsignedInteger8(5),
            new UnsignedInteger8(3),
            new UnsignedInteger32(86400),
            new UnsignedInteger32(1048354263),
            new UnsignedInteger32(1045762263),
            new UnsignedInteger16(2642),
            new FQDN('example','com',''),
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
        self::assertSame(DNSType::A,RRSIG::deserializeFromPresentationFormat('A 5 3 86400 20030322173103 20030220173103 2642 example.com. qrvM3Q==')->getFields()[0]->getValue());

        self::assertSame(1234,RRSIG::deserializeFromPresentationFormat('TYPE1234 5 3 86400 20030322173103 20030220173103 2642 example.com. qrvM3Q==')->getFields()[0]->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSMnemonicException
     * @throws DNSTypeException
     */
    public function testDeserializeFromPresentationFormatTooLessFields(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('RRSIG record should contain at least 8 fields.');

        RRSIG::deserializeFromPresentationFormat('');
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public function testDeserializeFromPresentationFormatUnknownType(): void{
        self::expectException(DNSMnemonicException::class);
        self::expectExceptionMessage('Invalid mnemonic key during deserialization.');

        RRSIG::deserializeFromPresentationFormat('NON-EXISTING 5 3 86400 20030322173103 20030220173103 2642 example.com. qrvM3Q==');
    }

}