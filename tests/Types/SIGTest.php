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
use YOCLIB\DNS\Types\SIG;

class SIGTest extends TestCase{

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public function testConstructor(): void{
        self::assertInstanceOf(SIG::class,new SIG([
            new UnsignedInteger16(DNSType::NXT),
            new UnsignedInteger8(1),
            new UnsignedInteger8(2),
            new UnsignedInteger32(3600),
            new UnsignedInteger32(852174245),
            new UnsignedInteger32(850298948),
            new UnsignedInteger16(2143),
            new FQDN('foo','nil',''),
            new Binary(hex2bin('0086000CFF1DDF360DC90C16D84338C1754576C94425C531FDFC647C1787D449788B13C58697C71449716EF2A85A6BE30D30A67E2632D97FBC5E9163C08087737F7C9335AC4CC2A5C68BE9CF61670933')),
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

        new SIG([
            new UnsignedInteger16(DNSType::NXT),
            new UnsignedInteger8(1),
            new UnsignedInteger8(2),
            new UnsignedInteger32(3600),
            new UnsignedInteger32(852174245),
            new UnsignedInteger32(850298948),
            new UnsignedInteger16(2143),
            new FQDN('foo','nil',''),
        ]);
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testConstructorInvalidFirstField(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('First field should be an UInt16.');

        new SIG([
            new UnsignedInteger8(DNSType::NXT),
            new UnsignedInteger8(1),
            new UnsignedInteger8(2),
            new UnsignedInteger32(3600),
            new UnsignedInteger32(852174245),
            new UnsignedInteger32(850298948),
            new UnsignedInteger16(2143),
            new FQDN('foo','nil',''),
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

        new SIG([
            new UnsignedInteger16(DNSType::NXT),
            new UnsignedInteger16(1),
            new UnsignedInteger8(2),
            new UnsignedInteger32(3600),
            new UnsignedInteger32(852174245),
            new UnsignedInteger32(850298948),
            new UnsignedInteger16(2143),
            new FQDN('foo','nil',''),
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

        new SIG([
            new UnsignedInteger16(DNSType::NXT),
            new UnsignedInteger8(1),
            new UnsignedInteger16(2),
            new UnsignedInteger32(3600),
            new UnsignedInteger32(852174245),
            new UnsignedInteger32(850298948),
            new UnsignedInteger16(2143),
            new FQDN('foo','nil',''),
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

        new SIG([
            new UnsignedInteger16(DNSType::NXT),
            new UnsignedInteger8(1),
            new UnsignedInteger8(2),
            new UnsignedInteger16(3600),
            new UnsignedInteger32(852174245),
            new UnsignedInteger32(850298948),
            new UnsignedInteger16(2143),
            new FQDN('foo','nil',''),
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

        new SIG([
            new UnsignedInteger16(DNSType::NXT),
            new UnsignedInteger8(1),
            new UnsignedInteger8(2),
            new UnsignedInteger32(3600),
            new UnsignedInteger64(852174245),
            new UnsignedInteger32(850298948),
            new UnsignedInteger16(2143),
            new FQDN('foo','nil',''),
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

        new SIG([
            new UnsignedInteger16(DNSType::NXT),
            new UnsignedInteger8(1),
            new UnsignedInteger8(2),
            new UnsignedInteger32(3600),
            new UnsignedInteger32(852174245),
            new UnsignedInteger64(850298948),
            new UnsignedInteger16(2143),
            new FQDN('foo','nil',''),
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

        new SIG([
            new UnsignedInteger16(DNSType::NXT),
            new UnsignedInteger8(1),
            new UnsignedInteger8(2),
            new UnsignedInteger32(3600),
            new UnsignedInteger32(852174245),
            new UnsignedInteger32(850298948),
            new UnsignedInteger32(2143),
            new FQDN('foo','nil',''),
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

        new SIG([
            new UnsignedInteger16(DNSType::NXT),
            new UnsignedInteger8(1),
            new UnsignedInteger8(2),
            new UnsignedInteger32(3600),
            new UnsignedInteger32(852174245),
            new UnsignedInteger32(850298948),
            new UnsignedInteger16(2143),
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

        new SIG([
            new UnsignedInteger16(DNSType::NXT),
            new UnsignedInteger8(1),
            new UnsignedInteger8(2),
            new UnsignedInteger32(3600),
            new UnsignedInteger32(852174245),
            new UnsignedInteger32(850298948),
            new UnsignedInteger16(2143),
            new FQDN('foo','nil',''),
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
//        self::assertSame('30 1 2 3600 19970102030405 19961211100908 2143 foo.nil. qrvM3Q==',(new SIG([
//            new UnsignedInteger16(DNSType::NXT),
//            new UnsignedInteger8(1),
//            new UnsignedInteger8(2),
//            new UnsignedInteger32(3600),
//            new UnsignedInteger32(852174245),
//            new UnsignedInteger32(850298948),
//            new UnsignedInteger16(2143),
//            new FQDN('foo','nil',''),
//            new Binary(hex2bin('AABBCCDD')),
//        ]))->serializeToPresentationFormat());
        self::assertSame('NXT 1 2 3600 19970102030405 19961211100908 2143 foo.nil. qrvM3Q==',(new SIG([
            new UnsignedInteger16(DNSType::NXT),
            new UnsignedInteger8(1),
            new UnsignedInteger8(2),
            new UnsignedInteger32(3600),
            new UnsignedInteger32(852174245),
            new UnsignedInteger32(850298948),
            new UnsignedInteger16(2143),
            new FQDN('foo','nil',''),
            new Binary(hex2bin('AABBCCDD')),
        ]))->serializeToPresentationFormat());

//        self::assertSame('30 1 2 19970102030405 19961211100908 2143 foo.nil. qrvM3Q==',(new SIG([
//            new UnsignedInteger16(DNSType::NXT),
//            new UnsignedInteger8(1),
//            new UnsignedInteger8(2),
//            new Binary(''),
//            new UnsignedInteger32(852174245),
//            new UnsignedInteger32(850298948),
//            new UnsignedInteger16(2143),
//            new FQDN('foo','nil',''),
//            new Binary(hex2bin('AABBCCDD')),
//        ]))->serializeToPresentationFormat());
        self::assertSame('NXT 1 2 19970102030405 19961211100908 2143 foo.nil. qrvM3Q==',(new SIG([
            new UnsignedInteger16(DNSType::NXT),
            new UnsignedInteger8(1),
            new UnsignedInteger8(2),
            new Binary(''),
            new UnsignedInteger32(852174245),
            new UnsignedInteger32(850298948),
            new UnsignedInteger16(2143),
            new FQDN('foo','nil',''),
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
        self::assertSame(DNSType::NXT,SIG::deserializeFromPresentationFormat('30 1 2 19970102030405 19961211100908 2143 foo.nil. qrvM3Q==')->getFields()[0]->getValue());
        self::assertSame(DNSType::NXT,SIG::deserializeFromPresentationFormat('30 1 2 3600 19970102030405 19961211100908 2143 foo.nil. qrvM3Q==')->getFields()[0]->getValue());
        self::assertSame(DNSType::NXT,SIG::deserializeFromPresentationFormat('NXT 1 2 19970102030405 19961211100908 2143 foo.nil. qrvM3Q==')->getFields()[0]->getValue());
        self::assertSame(DNSType::NXT,SIG::deserializeFromPresentationFormat('NXT 1 2 3600 19970102030405 19961211100908 2143 foo.nil. qrvM3Q==')->getFields()[0]->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSMnemonicException
     * @throws DNSTypeException
     */
    public function testDeserializeFromPresentationFormatTooLessFields(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('SIG record should contain at least 8 fields.');

        SIG::deserializeFromPresentationFormat('');
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public function testDeserializeFromPresentationFormatUnknownProtocol(): void{
        self::expectException(DNSMnemonicException::class);
        self::expectExceptionMessage('Invalid mnemonic key during deserialization.');

        SIG::deserializeFromPresentationFormat('NON-EXISTING 1 2 19970102030405 19961211100908 2143 foo.nil. qrvM3Q==');
    }

}