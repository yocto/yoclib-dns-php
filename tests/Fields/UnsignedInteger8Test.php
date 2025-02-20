<?php
namespace YOCLIB\DNS\Tests\Fields;

use PHPUnit\Framework\TestCase;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Fields\UnsignedInteger8;

class UnsignedInteger8Test extends TestCase{

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testConstructor(): void{
        self::assertInstanceOf(UnsignedInteger8::class,new UnsignedInteger8(0));
        self::assertInstanceOf(UnsignedInteger8::class,new UnsignedInteger8(127));
        self::assertInstanceOf(UnsignedInteger8::class,new UnsignedInteger8(128));
        self::assertInstanceOf(UnsignedInteger8::class,new UnsignedInteger8(255));
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testTooLow(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Human readable UInt8 should be in the range of 0 and 255.');

        new UnsignedInteger8(-512);
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testTooHigh(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Human readable UInt8 should be in the range of 0 and 255.');

        new UnsignedInteger8(512);
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testGetValue(): void{
        self::assertSame(0,(new UnsignedInteger8(0))->getValue());
        self::assertSame(127,(new UnsignedInteger8(127))->getValue());
        self::assertSame(128,(new UnsignedInteger8(128))->getValue());
        self::assertSame(255,(new UnsignedInteger8(255))->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testSerializeToPresentationFormat(): void{
        self::assertSame('0',(new UnsignedInteger8(0))->serializeToPresentationFormat());
        self::assertSame('127',(new UnsignedInteger8(127))->serializeToPresentationFormat());
        self::assertSame('128',(new UnsignedInteger8(128))->serializeToPresentationFormat());
        self::assertSame('255',(new UnsignedInteger8(255))->serializeToPresentationFormat());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testSerializeToWireFormat(): void{
        self::assertSame("\x00",(new UnsignedInteger8(0))->serializeToWireFormat());
        self::assertSame("\x7F",(new UnsignedInteger8(127))->serializeToWireFormat());
        self::assertSame("\x80",(new UnsignedInteger8(128))->serializeToWireFormat());
        self::assertSame("\xFF",(new UnsignedInteger8(255))->serializeToWireFormat());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromPresentationFormat(): void{
        self::assertSame(0,UnsignedInteger8::deserializeFromPresentationFormat('0')->getValue());
        self::assertSame(127,UnsignedInteger8::deserializeFromPresentationFormat('127')->getValue());
        self::assertSame(128,UnsignedInteger8::deserializeFromPresentationFormat('128')->getValue());
        self::assertSame(255,UnsignedInteger8::deserializeFromPresentationFormat('255')->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromWireFormat(): void{
        self::assertSame(0,UnsignedInteger8::deserializeFromWireFormat("\x00")->getValue());
        self::assertSame(127,UnsignedInteger8::deserializeFromWireFormat("\x7F")->getValue());
        self::assertSame(128,UnsignedInteger8::deserializeFromWireFormat("\x80")->getValue());
        self::assertSame(255,UnsignedInteger8::deserializeFromWireFormat("\xFF")->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromWireFormatTooLessData(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Binary UInt8 should be 1 octet.');

        UnsignedInteger8::deserializeFromWireFormat('');
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromWireFormatTooMuchData(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Binary UInt8 should be 1 octet.');

        UnsignedInteger8::deserializeFromWireFormat("\xAA\xBB");
    }

}