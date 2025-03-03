<?php
namespace YOCLIB\DNS\Tests\Fields;

use PHPUnit\Framework\TestCase;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Fields\Binary;

class BinaryTest extends TestCase{

    /**
     * @return void
     */
    public function testConstructor(): void{
        self::assertInstanceOf(Binary::class,new Binary(''));
        self::assertInstanceOf(Binary::class,new Binary('abc'));
        self::assertInstanceOf(Binary::class,new Binary('abcdef'));
        self::assertInstanceOf(Binary::class,new Binary("\x00\x7F\x80\xFF"));
    }

    /**
     * @return void
     */
    public function testGetValue(): void{
        self::assertSame('abc',(new Binary('abc'))->getValue());
        self::assertSame('abcdef',(new Binary('abcdef'))->getValue());
        self::assertSame("\x00\x7F\x80\xFF",(new Binary("\x00\x7F\x80\xFF"))->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testSerializeToPresentationFormat(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Cannot serialize binary. The presentation format is type dependent.');

        (new Binary("\x00\x7F\x80\xFF"))->serializeToPresentationFormat();
    }

    /**
     * @return void
     */
    public function testSerializeToWireFormat(): void{
        self::assertSame('abc',(new Binary('abc'))->serializeToWireFormat());
        self::assertSame('abcdef',(new Binary('abcdef'))->serializeToWireFormat());
        self::assertSame("\x00\x7F\x80\xFF",(new Binary("\x00\x7F\x80\xFF"))->serializeToWireFormat());
    }

    /**
     * @return void
     */
    public function testCalculateLength(): void{
        self::assertSame(3,Binary::calculateLength("abc"));
        self::assertSame(16,Binary::calculateLength("abctrailingBytes"));
        self::assertSame(6,Binary::calculateLength("abcdef"));
        self::assertSame(19,Binary::calculateLength("abcdeftrailingBytes"));
        self::assertSame(4,Binary::calculateLength("\x00\x7F\x80\xFF"));
        self::assertSame(17,Binary::calculateLength("\x00\x7F\x80\xFFtrailingBytes"));
    }

    /**
     * @return void
     */
    public function testDeserializeFromPresentationFormat(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Cannot deserialize binary. The presentation format is type dependent.');

        Binary::deserializeFromPresentationFormat("\x00\x7F\x80\xFF")->getValue();
    }

    /**
     * @return void
     */
    public function testDeserializeFromWireFormat(): void{
        self::assertSame('abc',Binary::deserializeFromWireFormat('abc')->getValue());
        self::assertSame('abcdef',Binary::deserializeFromWireFormat('abcdef')->getValue());
        self::assertSame("\x00\x7F\x80\xFF",Binary::deserializeFromWireFormat("\x00\x7F\x80\xFF")->getValue());
    }

}