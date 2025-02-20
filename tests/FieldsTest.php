<?php
namespace YOCLIB\DNS\Tests;

use PHPUnit\Framework\TestCase;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Fields\UnsignedInteger16;
use YOCLIB\DNS\Fields\UnsignedInteger32;

class FieldsTest extends TestCase{

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testGetValue(): void{
        self::assertSame(12345678,(new UnsignedInteger32(12345678))->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testSerializeToPresentationFormat(): void{
        self::assertSame('12345678',(new UnsignedInteger32(12345678))->serializeToPresentationFormat());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testSerializeToWireFormat(): void{
        self::assertSame(pack('N',12345678),(new UnsignedInteger32(12345678))->serializeToWireFormat());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testAll(): void{
        self::assertEquals("\x7F\x7F\x7F\x7F",UnsignedInteger32::deserializeFromPresentationFormat('2139062143')->serializeToWireFormat());
        self::assertEquals("2139062143",UnsignedInteger32::deserializeFromWireFormat("\x7F\x7F\x7F\x7F")->serializeToPresentationFormat());
    }

}