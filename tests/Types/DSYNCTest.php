<?php
namespace YOCLIB\DNS\Tests\Types;

use PHPUnit\Framework\TestCase;

use YOCLIB\DNS\DNSType;
use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSMnemonicException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Binary;
use YOCLIB\DNS\Fields\FQDN;
use YOCLIB\DNS\Fields\IPv4Address;
use YOCLIB\DNS\Fields\UnsignedInteger16;
use YOCLIB\DNS\Fields\UnsignedInteger32;
use YOCLIB\DNS\Fields\UnsignedInteger8;
use YOCLIB\DNS\Types\DSYNC;

class DSYNCTest extends TestCase{

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public function testConstructor(): void{
        self::assertInstanceOf(DSYNC::class,new DSYNC([
            new UnsignedInteger16(DNSType::CDS),
            new UnsignedInteger8(1),
            new UnsignedInteger16(5300),
            new FQDN('rr-endpoint','example',''),
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

        new DSYNC([
            new UnsignedInteger16(DNSType::CDS),
            new UnsignedInteger8(1),
            new UnsignedInteger16(5300),
        ]);
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testConstructorInvalidFirstField(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('First field should be an UInt16.');

        new DSYNC([
            new UnsignedInteger32(DNSType::CDS),
            new UnsignedInteger8(1),
            new UnsignedInteger16(5300),
            new FQDN('rr-endpoint','example',''),
        ]);
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testConstructorInvalidSecondField(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('Second field should be an UInt8.');

        new DSYNC([
            new UnsignedInteger16(DNSType::CDS),
            new UnsignedInteger16(1),
            new UnsignedInteger16(5300),
            new FQDN('rr-endpoint','example',''),
        ]);
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testConstructorInvalidThirdField(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('Third field should be an UInt16.');

        new DSYNC([
            new UnsignedInteger16(DNSType::CDS),
            new UnsignedInteger8(1),
            new UnsignedInteger32(5300),
            new FQDN('rr-endpoint','example',''),
        ]);
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testConstructorInvalidFourthField(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('Fourth field should be a FQDN.');

        new DSYNC([
            new UnsignedInteger16(DNSType::CDS),
            new UnsignedInteger8(1),
            new UnsignedInteger16(5300),
            new IPv4Address('127.0.0.1'),
        ]);
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSMnemonicException
     * @throws DNSTypeException
     */
    public function testSerializeToPresentationFormat(): void{
        self::assertSame('CDS 1 5300 rr-endpoint.example.',(new DSYNC([
            new UnsignedInteger16(DNSType::CDS),
            new UnsignedInteger8(1),
            new UnsignedInteger16(5300),
            new FQDN('rr-endpoint','example',''),
        ]))->serializeToPresentationFormat());

        self::assertSame('TYPE1234 1 5300 rr-endpoint.example.',(new DSYNC([
            new UnsignedInteger16(1234),
            new UnsignedInteger8(1),
            new UnsignedInteger16(5300),
            new FQDN('rr-endpoint','example',''),
        ]))->serializeToPresentationFormat());
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSMnemonicException
     * @throws DNSTypeException
     */
    public function testDeserializeFromPresentationFormat(): void{
        self::assertSame(DNSType::CDS,DSYNC::deserializeFromPresentationFormat('CDS 1 5300 rr-endpoint.example.')->getFields()[0]->getValue());

        self::assertSame(1234,DSYNC::deserializeFromPresentationFormat('TYPE1234 1 5300 rr-endpoint.example.')->getFields()[0]->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSMnemonicException
     * @throws DNSTypeException
     */
    public function testDeserializeFromPresentationFormatTooLessFields(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('DSYNC record should contain 4 fields.');

        DSYNC::deserializeFromPresentationFormat('');
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public function testDeserializeFromPresentationFormatUnknownProtocol(): void{
        self::expectException(DNSMnemonicException::class);
        self::expectExceptionMessage('Invalid mnemonic key during deserialization.');

        DSYNC::deserializeFromPresentationFormat('NON-EXISTING 1 5300 rr-endpoint.example.');
    }

}