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
use YOCLIB\DNS\Types\DS;

class DSTest extends TestCase{

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public function testConstructor(): void{
        self::assertInstanceOf(DS::class,new DS([
            new UnsignedInteger16(60485),
            new UnsignedInteger8(5),
            new UnsignedInteger8(1),
            new Binary(hex2bin('2BB183AF5F22588179A53B0A98631FAD1A292118')),
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

        new DS([
            new UnsignedInteger16(60485),
            new UnsignedInteger8(5),
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

        new DS([
            new UnsignedInteger32(60485),
            new UnsignedInteger8(5),
            new UnsignedInteger8(1),
            new Binary(hex2bin('2BB183AF5F22588179A53B0A98631FAD1A292118')),
        ]);
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testConstructorInvalidSecondField(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('Second field should be an UInt8.');

        new DS([
            new UnsignedInteger16(60485),
            new UnsignedInteger16(5),
            new UnsignedInteger8(1),
            new Binary(hex2bin('2BB183AF5F22588179A53B0A98631FAD1A292118')),
        ]);
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testConstructorInvalidThirdField(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('Third field should be an UInt8.');

        new DS([
            new UnsignedInteger16(60485),
            new UnsignedInteger8(5),
            new UnsignedInteger16(1),
            new Binary(hex2bin('2BB183AF5F22588179A53B0A98631FAD1A292118')),
        ]);
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testConstructorInvalidFourthField(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('Fourth field should be binary.');

        new DS([
            new UnsignedInteger16(60485),
            new UnsignedInteger8(5),
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
//        self::assertSame('60485 5 1 2BB183AF5F22588179A53B0A98631FAD1A292118',(new DS([
//            new UnsignedInteger16(60485),
//            new UnsignedInteger8(5),
//            new UnsignedInteger8(1),
//            new Binary(hex2bin('2BB183AF5F22588179A53B0A98631FAD1A292118')),
//        ]))->serializeToPresentationFormat());
        self::assertSame('60485 RSASHA1 1 2BB183AF5F22588179A53B0A98631FAD1A292118',(new DS([
            new UnsignedInteger16(60485),
            new UnsignedInteger8(5),
            new UnsignedInteger8(1),
            new Binary(hex2bin('2BB183AF5F22588179A53B0A98631FAD1A292118')),
        ]))->serializeToPresentationFormat());
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSMnemonicException
     * @throws DNSTypeException
     */
    public function testDeserializeFromPresentationFormat(): void{
        self::assertSame(5,DS::deserializeFromPresentationFormat('60485 5 1 2BB183AF5F22588179A53B0A98631FAD1A292118')->getFields()[1]->getValue());
        self::assertSame(5,DS::deserializeFromPresentationFormat('60485 RSASHA1 1 2BB183AF5F22588179A53B0A98631FAD1A292118')->getFields()[1]->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSMnemonicException
     * @throws DNSTypeException
     */
    public function testDeserializeFromPresentationFormatTooLessFields(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('DS record should contain at least 4 fields.');

        DS::deserializeFromPresentationFormat('');
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public function testDeserializeFromPresentationFormatUnknownProtocol(): void{
        self::expectException(DNSMnemonicException::class);
        self::expectExceptionMessage('Invalid mnemonic key during deserialization.');

        DS::deserializeFromPresentationFormat('60485 NON-EXISTING 1 2BB183AF5F22588179A53B0A98631FAD1A292118');
    }

}