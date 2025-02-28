<?php
namespace YOCLIB\DNS\Tests\Fields;

use PHPUnit\Framework\TestCase;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Fields\UnsignedInteger32;

class UnsignedInteger32Test extends TestCase{

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testConstructor(): void{
        self::assertInstanceOf(UnsignedInteger32::class,new UnsignedInteger32(0));
        self::assertInstanceOf(UnsignedInteger32::class,new UnsignedInteger32(2139062143));
        self::assertInstanceOf(UnsignedInteger32::class,new UnsignedInteger32(2155905152));
        self::assertInstanceOf(UnsignedInteger32::class,new UnsignedInteger32(4294967295));
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testTooLow(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Human readable UInt32 should be in the range of 0 and 4294967295.');

        new UnsignedInteger32(-8589934592);
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testTooHigh(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Human readable UInt32 should be in the range of 0 and 4294967295.');

        new UnsignedInteger32(8589934592);
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testGetValue(): void{
        self::assertSame(0,(new UnsignedInteger32(0))->getValue());
        self::assertSame(2139062143,(new UnsignedInteger32(2139062143))->getValue());
        self::assertSame(2155905152,(new UnsignedInteger32(2155905152))->getValue());
        self::assertSame(4294967295,(new UnsignedInteger32(4294967295))->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testSerializeToPresentationFormat(): void{
        self::assertSame('0',(new UnsignedInteger32(0))->serializeToPresentationFormat());
        self::assertSame('2139062143',(new UnsignedInteger32(2139062143))->serializeToPresentationFormat());
        self::assertSame('2155905152',(new UnsignedInteger32(2155905152))->serializeToPresentationFormat());
        self::assertSame('4294967295',(new UnsignedInteger32(4294967295))->serializeToPresentationFormat());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testSerializeToWireFormat(): void{
        self::assertSame("\x00\x00\x00\x00",(new UnsignedInteger32(0))->serializeToWireFormat());
        self::assertSame("\x7F\x7F\x7F\x7F",(new UnsignedInteger32(2139062143))->serializeToWireFormat());
        self::assertSame("\x80\x80\x80\x80",(new UnsignedInteger32(2155905152))->serializeToWireFormat());
        self::assertSame("\xFF\xFF\xFF\xFF",(new UnsignedInteger32(4294967295))->serializeToWireFormat());
    }

    /**
     * @return void
     */
    public function testCalculateLength(): void{
        self::assertSame(4,UnsignedInteger32::calculateLength("\x00\x00\x00\x00"));
        self::assertSame(4,UnsignedInteger32::calculateLength("\x00\x00\x00\x00trailingBytes"));
        self::assertSame(4,UnsignedInteger32::calculateLength("\x7F\x7F\x7F\x7F"));
        self::assertSame(4,UnsignedInteger32::calculateLength("\x7F\x7F\x7F\x7FtrailingBytes"));
        self::assertSame(4,UnsignedInteger32::calculateLength("\x80\x80\x80\x80"));
        self::assertSame(4,UnsignedInteger32::calculateLength("\x80\x80\x80\x80trailingBytes"));
        self::assertSame(4,UnsignedInteger32::calculateLength("\xFF\xFF\xFF\xFF"));
        self::assertSame(4,UnsignedInteger32::calculateLength("\xFF\xFF\xFF\xFFtrailingBytes"));
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromPresentationFormat(): void{
        self::assertSame(0,UnsignedInteger32::deserializeFromPresentationFormat('0')->getValue());
        self::assertSame(2139062143,UnsignedInteger32::deserializeFromPresentationFormat('2139062143')->getValue());
        self::assertSame(2155905152,UnsignedInteger32::deserializeFromPresentationFormat('2155905152')->getValue());
        self::assertSame(4294967295,UnsignedInteger32::deserializeFromPresentationFormat('4294967295')->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromPresentationFormatNoInteger(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Human readable UInt32 should only contain digits.');

        UnsignedInteger32::deserializeFromPresentationFormat('abc');
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromWireFormat(): void{
        self::assertSame(0,UnsignedInteger32::deserializeFromWireFormat("\x00\x00\x00\x00")->getValue());
        self::assertSame(2139062143,UnsignedInteger32::deserializeFromWireFormat("\x7F\x7F\x7F\x7F")->getValue());
        self::assertSame(2155905152,UnsignedInteger32::deserializeFromWireFormat("\x80\x80\x80\x80")->getValue());
        self::assertSame(4294967295,UnsignedInteger32::deserializeFromWireFormat("\xFF\xFF\xFF\xFF")->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromWireFormatTooLessData(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Binary UInt32 should be 4 octets.');

        UnsignedInteger32::deserializeFromWireFormat("\xAA\xBB");
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromWireFormatTooMuchData(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Binary UInt32 should be 4 octets.');

        UnsignedInteger32::deserializeFromWireFormat("\xAA\xBB\xCC\xDD\xEE\xFF\x00\x11");
    }

}