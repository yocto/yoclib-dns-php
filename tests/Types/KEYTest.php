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
use YOCLIB\DNS\Fields\UnsignedInteger16;
use YOCLIB\DNS\Fields\UnsignedInteger32;
use YOCLIB\DNS\Fields\UnsignedInteger8;
use YOCLIB\DNS\Types\KEY;

class KEYTest extends TestCase{

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public function testConstructor(): void{
        self::assertInstanceOf(KEY::class,new KEY([
            new UnsignedInteger16(1),
            new UnsignedInteger8(1),
            new UnsignedInteger8(1),
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

        new KEY([
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

        new KEY([
            new UnsignedInteger32(1),
            new UnsignedInteger8(1),
            new UnsignedInteger8(1),
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

        new KEY([
            new UnsignedInteger16(1),
            new UnsignedInteger16(1),
            new UnsignedInteger8(1),
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

        new KEY([
            new UnsignedInteger16(1),
            new UnsignedInteger8(1),
            new UnsignedInteger16(1),
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

        new KEY([
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
//        self::assertSame('1 1 1 qrvM3Q==',(new KEY([
//            new UnsignedInteger16(1),
//            new UnsignedInteger8(1),
//            new UnsignedInteger8(1),
//            new Binary(hex2bin('AABBCCDD')),
//        ]))->serializeToPresentationFormat());
        self::assertSame('1 TLS 1 qrvM3Q==',(new KEY([
            new UnsignedInteger16(1),
            new UnsignedInteger8(1),
            new UnsignedInteger8(1),
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
        self::assertSame(1,KEY::deserializeFromPresentationFormat('1 1 1 qrvM3Q==')->getFields()[1]->getValue());
        self::assertSame(1,KEY::deserializeFromPresentationFormat('1 TLS 1 qrvM3Q==')->getFields()[1]->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSMnemonicException
     * @throws DNSTypeException
     */
    public function testDeserializeFromPresentationFormatTooLessFields(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('KEY record should contain at least 3 fields.');

        KEY::deserializeFromPresentationFormat('');
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public function testDeserializeFromPresentationFormatUnknownProtocol(): void{
        self::expectException(DNSMnemonicException::class);
        self::expectExceptionMessage('Invalid mnemonic key during deserialization.');

        KEY::deserializeFromPresentationFormat('1 NON-EXISTING 1 qrvM3Q==');
    }

}