<?php
namespace YOCLIB\DNS\Tests\Types;

use PHPUnit\Framework\TestCase;

use YOCLIB\DNS\DNSType;
use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSMnemonicException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Binary;
use YOCLIB\DNS\Fields\UnsignedInteger16;
use YOCLIB\DNS\Fields\UnsignedInteger32;
use YOCLIB\DNS\Fields\UnsignedInteger8;
use YOCLIB\DNS\Fields\WindowBlockBitmap;
use YOCLIB\DNS\Types\CSYNC;

class CSYNCTest extends TestCase{

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public function testConstructor(): void{
        self::assertInstanceOf(CSYNC::class,new CSYNC([
            new UnsignedInteger32(66),
            new UnsignedInteger16(3),
            new WindowBlockBitmap([DNSType::A,DNSType::NS,DNSType::AAAA]),
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

        new CSYNC([
            new UnsignedInteger32(66),
            new UnsignedInteger16(3),
        ]);
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public function testConstructorInvalidFirstField(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('First field should be an UInt32.');

        new CSYNC([
            new UnsignedInteger8(66),
            new UnsignedInteger16(3),
            new WindowBlockBitmap([DNSType::A,DNSType::NS,DNSType::AAAA]),
        ]);
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public function testConstructorInvalidSecondField(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('Second field should be an UInt16.');

        new CSYNC([
            new UnsignedInteger32(66),
            new UnsignedInteger8(3),
            new WindowBlockBitmap([DNSType::A,DNSType::NS,DNSType::AAAA]),
        ]);
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public function testConstructorInvalidThirdField(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('Third field should be a window block bitmap.');

        new CSYNC([
            new UnsignedInteger32(66),
            new UnsignedInteger16(3),
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
        self::assertSame('66 3',(new CSYNC([
            new UnsignedInteger32(66),
            new UnsignedInteger16(3),
            new WindowBlockBitmap([]),
        ]))->serializeToPresentationFormat());

        self::assertSame('66 3 A NS AAAA',(new CSYNC([
            new UnsignedInteger32(66),
            new UnsignedInteger16(3),
            new WindowBlockBitmap([DNSType::A,DNSType::NS,DNSType::AAAA]),
        ]))->serializeToPresentationFormat());

        self::assertSame('66 3 TYPE1234',(new CSYNC([
            new UnsignedInteger32(66),
            new UnsignedInteger16(3),
            new WindowBlockBitmap([1234]),
        ]))->serializeToPresentationFormat());
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSMnemonicException
     * @throws DNSTypeException
     */
    public function testDeserializeFromPresentationFormat(): void{
        self::assertSame([],CSYNC::deserializeFromPresentationFormat('66 3')->getFields()[2]->getValue());

        self::assertSame([DNSType::A,DNSType::NS,DNSType::AAAA],CSYNC::deserializeFromPresentationFormat('66 3 A NS AAAA')->getFields()[2]->getValue());

        self::assertSame([1234],CSYNC::deserializeFromPresentationFormat('66 3 TYPE1234')->getFields()[2]->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSMnemonicException
     * @throws DNSTypeException
     */
    public function testDeserializeFromPresentationFormatTooLessFields(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('CSYNC record should contain at least 2 fields.');

        CSYNC::deserializeFromPresentationFormat('');
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSMnemonicException
     * @throws DNSTypeException
     */
    public function testDeserializeFromPresentationFormatUnknownBitmapMnemonic(): void{
        self::expectException(DNSMnemonicException::class);
        self::expectExceptionMessage('Invalid mnemonic key during deserialization.');

        CSYNC::deserializeFromPresentationFormat('66 3 NON-EXISTING');
    }

}