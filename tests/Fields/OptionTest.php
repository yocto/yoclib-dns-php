<?php
namespace YOCLIB\DNS\Tests\Fields;

use PHPUnit\Framework\TestCase;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Fields\Option;

class OptionTest extends TestCase{

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testConstructor(): void{
        self::assertInstanceOf(Option::class,new Option(19,"\x02\x00\x78\x95\xA4\xE9"));
    }

    /**
     * @return void
     */
    public function testConstructorCodeTooLarge(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Option code should be between 0 and 65535.');

        new Option(1234567,"\x02\x00\x78\x95\xA4\xE9");
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testGetValue(): void{
        self::assertSame([19,"\x02\x00\x78\x95\xA4\xE9"],(new Option(19,"\x02\x00\x78\x95\xA4\xE9"))->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testSerializeToPresentationFormat(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Cannot serialize option.');

        (new Option(19,"\x02\x00\x78\x95\xA4\xE9"))->serializeToPresentationFormat();
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testSerializeToWireFormat(): void{
        self::assertSame("\x00\x13\x00\x06\x02\x00\x78\x95\xA4\xE9",(new Option(19,"\x02\x00\x78\x95\xA4\xE9"))->serializeToWireFormat());
    }

    /**
     * @return void
     */
    public function testCalculateLength(): void{
        self::assertSame(10,Option::calculateLength("\x00\x13\x00\x06\x02\x00\x78\x95\xA4\xE9"));
        self::assertSame(10,Option::calculateLength("\x00\x13\x00\x06\x02\x00\x78\x95\xA4\xE9trailingBytes"));
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromPresentationFormat(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Cannot deserialize option.');

        Option::deserializeFromPresentationFormat('abcdef');
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromWireFormat(): void{
        self::assertSame([19,"\x02\x00\x78\x95\xA4\xE9"],Option::deserializeFromWireFormat("\x00\x13\x00\x06\x02\x00\x78\x95\xA4\xE9")->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromWireFormatTooLessData(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Option should be at least 4 octets.');

        Option::deserializeFromWireFormat("\xAA\xBB\xCC");
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromWireFormatTooLessOptionData(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Too less data available to read option.');

        Option::deserializeFromWireFormat("\x00\x13\x00\x04\xAA\xBB");
    }

}