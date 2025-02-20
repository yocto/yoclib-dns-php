<?php
namespace YOCLIB\DNS\Tests;

use PHPUnit\Framework\TestCase;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Fields\IPv4Address;
use YOCLIB\DNS\Fields\IPv6Address;
use YOCLIB\DNS\Fields\UnsignedInteger16;
use YOCLIB\DNS\Fields\UnsignedInteger32;
use YOCLIB\DNS\Fields\UnsignedInteger8;

class FieldsTest extends TestCase{

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testGetValue(): void{
        self::assertSame('1.2.3.4',(new IPv4Address('1.2.3.4'))->getValue());
        self::assertSame('::',(new IPv6Address('::'))->getValue());
        self::assertSame(123,(new UnsignedInteger8(123))->getValue());
        self::assertSame(1234,(new UnsignedInteger16(1234))->getValue());
        self::assertSame(12345678,(new UnsignedInteger32(12345678))->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testSerializeToPresentationFormat(): void{
        self::assertSame('1.2.3.4',(new IPv4Address('1.2.3.4'))->serializeToPresentationFormat());
        self::assertSame('::',(new IPv6Address('::'))->serializeToPresentationFormat());
        self::assertSame('123',(new UnsignedInteger8(123))->serializeToPresentationFormat());
        self::assertSame('1234',(new UnsignedInteger16(1234))->serializeToPresentationFormat());
        self::assertSame('12345678',(new UnsignedInteger32(12345678))->serializeToPresentationFormat());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testSerializeToWireFormat(): void{
        self::assertSame("\x01\x02\x03\x04",(new IPv4Address('1.2.3.4'))->serializeToWireFormat());
        self::assertSame("\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",(new IPv6Address('::'))->serializeToWireFormat());
        self::assertSame(chr(123),(new UnsignedInteger8(123))->serializeToWireFormat());
        self::assertSame(pack('n',1234),(new UnsignedInteger16(1234))->serializeToWireFormat());
        self::assertSame(pack('N',12345678),(new UnsignedInteger32(12345678))->serializeToWireFormat());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testAll(): void{
        self::assertEquals("\x01\x02\x03\x04",IPv4Address::deserializeFromPresentationFormat('1.2.3.4')->serializeToWireFormat());
        self::assertEquals("1.2.3.4",IPv4Address::deserializeFromWireFormat("\x01\x02\x03\x04")->serializeToPresentationFormat());

        self::assertEquals("\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",IPv6Address::deserializeFromPresentationFormat('::')->serializeToWireFormat());
        self::assertEquals("::",IPv6Address::deserializeFromWireFormat("\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00")->serializeToPresentationFormat());

        self::assertEquals("\x7F",UnsignedInteger8::deserializeFromPresentationFormat('127')->serializeToWireFormat());
        self::assertEquals("127",UnsignedInteger8::deserializeFromWireFormat("\x7F")->serializeToPresentationFormat());

        self::assertEquals("\x7F\x7F",UnsignedInteger16::deserializeFromPresentationFormat('32639')->serializeToWireFormat());
        self::assertEquals("32639",UnsignedInteger16::deserializeFromWireFormat("\x7F\x7F")->serializeToPresentationFormat());

        self::assertEquals("\x7F\x7F\x7F\x7F",UnsignedInteger32::deserializeFromPresentationFormat('2139062143')->serializeToWireFormat());
        self::assertEquals("2139062143",UnsignedInteger32::deserializeFromWireFormat("\x7F\x7F\x7F\x7F")->serializeToPresentationFormat());
    }

}