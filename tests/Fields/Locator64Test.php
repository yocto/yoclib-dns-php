<?php
namespace YOCLIB\DNS\Tests\Fields;

use PHPUnit\Framework\TestCase;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Fields\Locator64;

class Locator64Test extends TestCase{

    /**
     * @return void
     */
    public function testConstructor(): void{
        self::assertInstanceOf(Locator64::class,new Locator64('2001:0DB8:1140:1000'));
    }

    /**
     * @return void
     */
    public function testConstructorNotIPv4(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('A Locator64 should be 4 groups of hexadecimal digits, separated by colons.');

        new Locator64('2001_0DB8:1140:1000');
    }

    /**
     * @return void
     */
    public function testGetValue(): void{
        self::assertSame('2001:0DB8:1140:1000',(new Locator64('2001:0DB8:1140:1000'))->getValue());
    }

    /**
     * @return void
     */
    public function testSerializeToPresentationFormat(): void{
        self::assertSame('2001:0DB8:1140:1000',(new Locator64('2001:0DB8:1140:1000'))->serializeToPresentationFormat());
    }

    /**
     * @return void
     */
    public function testSerializeToWireFormat(): void{
        self::assertSame("\x20\x01\x0D\xB8\x11\x40\x10\x00",(new Locator64('2001:0DB8:1140:1000'))->serializeToWireFormat());
    }

    /**
     * @return void
     */
    public function testCalculateLength(): void{
        self::assertSame(8,Locator64::calculateLength("\x20\x01\x0D\xB8\x11\x40\x10\x00"));
        self::assertSame(8,Locator64::calculateLength("\x20\x01\x0D\xB8\x11\x40\x10\x00trailingBytes"));
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromPresentationFormat(): void{
        self::assertSame('2001:0DB8:1140:1000',Locator64::deserializeFromPresentationFormat('2001:0DB8:1140:1000')->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromWireFormat(): void{
        self::assertSame('2001:0DB8:1140:1000',Locator64::deserializeFromWireFormat("\x20\x01\x0D\xB8\x11\x40\x10\x00")->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromWireFormatTooLessData(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Binary Locator64 should be 8 octets.');

        Locator64::deserializeFromWireFormat("\x20\x01\x0D\xB8\x11\x40");
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromWireFormatTooMuchData(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Binary Locator64 should be 8 octets.');

        Locator64::deserializeFromWireFormat("\x20\x01\x0D\xB8\x11\x40\x10\x00\xAA\xBB");
    }

}