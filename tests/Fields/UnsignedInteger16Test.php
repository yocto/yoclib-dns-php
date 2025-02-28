<?php
namespace YOCLIB\DNS\Tests\Fields;

use PHPUnit\Framework\TestCase;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Fields\UnsignedInteger16;

class UnsignedInteger16Test extends TestCase{

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testConstructor(): void{
        self::assertInstanceOf(UnsignedInteger16::class,new UnsignedInteger16(0));
        self::assertInstanceOf(UnsignedInteger16::class,new UnsignedInteger16(32639));
        self::assertInstanceOf(UnsignedInteger16::class,new UnsignedInteger16(32896));
        self::assertInstanceOf(UnsignedInteger16::class,new UnsignedInteger16(65535));
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testTooLow(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Human readable UInt16 should be in the range of 0 and 65535.');

        new UnsignedInteger16(-131072);
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testTooHigh(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Human readable UInt16 should be in the range of 0 and 65535.');

        new UnsignedInteger16(131072);
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testGetValue(): void{
        self::assertSame(0,(new UnsignedInteger16(0))->getValue());
        self::assertSame(32639,(new UnsignedInteger16(32639))->getValue());
        self::assertSame(32896,(new UnsignedInteger16(32896))->getValue());
        self::assertSame(65535,(new UnsignedInteger16(65535))->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testSerializeToPresentationFormat(): void{
        self::assertSame('0',(new UnsignedInteger16(0))->serializeToPresentationFormat());
        self::assertSame('32639',(new UnsignedInteger16(32639))->serializeToPresentationFormat());
        self::assertSame('32896',(new UnsignedInteger16(32896))->serializeToPresentationFormat());
        self::assertSame('65535',(new UnsignedInteger16(65535))->serializeToPresentationFormat());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testSerializeToWireFormat(): void{
        self::assertSame("\x00\x00",(new UnsignedInteger16(0))->serializeToWireFormat());
        self::assertSame("\x7F\x7F",(new UnsignedInteger16(32639))->serializeToWireFormat());
        self::assertSame("\x80\x80",(new UnsignedInteger16(32896))->serializeToWireFormat());
        self::assertSame("\xFF\xFF",(new UnsignedInteger16(65535))->serializeToWireFormat());
    }

    /**
     * @return void
     */
    public function testCalculateLength(): void{
        self::assertSame(2,UnsignedInteger16::calculateLength("\x00\x00"));
        self::assertSame(2,UnsignedInteger16::calculateLength("\x00\x00trailingBytes"));
        self::assertSame(2,UnsignedInteger16::calculateLength("\x7F\x7F"));
        self::assertSame(2,UnsignedInteger16::calculateLength("\x7F\x7FtrailingBytes"));
        self::assertSame(2,UnsignedInteger16::calculateLength("\x80\x80"));
        self::assertSame(2,UnsignedInteger16::calculateLength("\x80\x80trailingBytes"));
        self::assertSame(2,UnsignedInteger16::calculateLength("\xFF\xFF"));
        self::assertSame(2,UnsignedInteger16::calculateLength("\xFF\xFFtrailingBytes"));
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromPresentationFormat(): void{
        self::assertSame(0,UnsignedInteger16::deserializeFromPresentationFormat('0')->getValue());
        self::assertSame(32639,UnsignedInteger16::deserializeFromPresentationFormat('32639')->getValue());
        self::assertSame(32896,UnsignedInteger16::deserializeFromPresentationFormat('32896')->getValue());
        self::assertSame(65535,UnsignedInteger16::deserializeFromPresentationFormat('65535')->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromPresentationFormatNoInteger(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Human readable UInt16 should only contain digits.');

        UnsignedInteger16::deserializeFromPresentationFormat('abc');
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromWireFormat(): void{
        self::assertSame(0,UnsignedInteger16::deserializeFromWireFormat("\x00\x00")->getValue());
        self::assertSame(32639,UnsignedInteger16::deserializeFromWireFormat("\x7F\x7F")->getValue());
        self::assertSame(32896,UnsignedInteger16::deserializeFromWireFormat("\x80\x80")->getValue());
        self::assertSame(65535,UnsignedInteger16::deserializeFromWireFormat("\xFF\xFF")->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromWireFormatTooLessData(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Binary UInt16 should be 2 octets.');

        UnsignedInteger16::deserializeFromWireFormat("\xAA");
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromWireFormatTooMuchData(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Binary UInt16 should be 2 octets.');

        UnsignedInteger16::deserializeFromWireFormat("\xAA\xBB\xCC\xDD");
    }

}