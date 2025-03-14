<?php
namespace YOCLIB\DNS\Tests\Fields;

use PHPUnit\Framework\TestCase;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Fields\CharacterString;

class CharacterStringTest extends TestCase{

    /**
     * @return void
     */
    public function testConstructor(): void{
        self::assertInstanceOf(CharacterString::class,new CharacterString(''));
        self::assertInstanceOf(CharacterString::class,new CharacterString('Text'));
        self::assertInstanceOf(CharacterString::class,new CharacterString('Text with space'));
        self::assertInstanceOf(CharacterString::class,new CharacterString('Text "with" quote'));
    }

    /**
     * @return void
     */
    public function testConstructorTooLong(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Character string can have 255 characters at most.');

        new CharacterString('0123456789ABCDEF0123456789ABCDEF0123456789ABCDEF0123456789ABCDEF0123456789ABCDEF0123456789ABCDEF0123456789ABCDEF0123456789ABCDEF0123456789ABCDEF0123456789ABCDEF0123456789ABCDEF0123456789ABCDEF0123456789ABCDEF0123456789ABCDEF0123456789ABCDEF0123456789ABCDEF');
    }

    /**
     * @return void
     */
    public function testGetValue(): void{
        self::assertSame('',(new CharacterString(''))->getValue());
        self::assertSame('Text',(new CharacterString('Text'))->getValue());
        self::assertSame('Text with space',(new CharacterString('Text with space'))->getValue());
        self::assertSame('Text "with" quote',(new CharacterString('Text "with" quote'))->getValue());
    }

    /**
     * @return void
     */
    public function testSerializeToPresentationFormat(): void{
        self::assertSame('""',(new CharacterString(''))->serializeToPresentationFormat());
        self::assertSame('Text',(new CharacterString('Text'))->serializeToPresentationFormat());
        self::assertSame('"Text"',(new CharacterString('Text'))->serializeToPresentationFormat(true));
        self::assertSame('"Text with space"',(new CharacterString('Text with space'))->serializeToPresentationFormat());
        self::assertSame('"Text \"with\" quote"',(new CharacterString('Text "with" quote'))->serializeToPresentationFormat());
    }

    /**
     * @return void
     */
    public function testSerializeToWireFormat(): void{
        self::assertSame("\x00",(new CharacterString(''))->serializeToWireFormat());
        self::assertSame("\x04Text",(new CharacterString('Text'))->serializeToWireFormat());
        self::assertSame("\x0FText with space",(new CharacterString('Text with space'))->serializeToWireFormat());
        self::assertSame("\x11Text \"with\" quote",(new CharacterString('Text "with" quote'))->serializeToWireFormat());
    }

    /**
     * @return void
     */
    public function testCalculateLength(): void{
        self::assertSame(0,CharacterString::calculateLength(''));
        self::assertSame(1,CharacterString::calculateLength("\x00"));
        self::assertSame(1,CharacterString::calculateLength("\x00trailingBytes"));
        self::assertSame(5,CharacterString::calculateLength("\x04Text"));
        self::assertSame(5,CharacterString::calculateLength("\x04TexttrailingBytes"));
        self::assertSame(16,CharacterString::calculateLength("\x0FText with space"));
        self::assertSame(16,CharacterString::calculateLength("\x0FText with spacetrailingBytes"));
        self::assertSame(18,CharacterString::calculateLength("\x11Text \"with\" quote"));
        self::assertSame(18,CharacterString::calculateLength("\x11Text \"with\" quotetrailingBytes"));
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromPresentationFormat(): void{
        self::assertSame('',CharacterString::deserializeFromPresentationFormat('""')->getValue());
        self::assertSame('Text',CharacterString::deserializeFromPresentationFormat('Text')->getValue());
        self::assertSame('Text with space',CharacterString::deserializeFromPresentationFormat('"Text with space"')->getValue());
        self::assertSame('Text "with" quote',CharacterString::deserializeFromPresentationFormat('"Text \"with\" quote"')->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromPresentationFormatMissingQuote(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Quoted character string should also end with a quote.');

        CharacterString::deserializeFromPresentationFormat('"THIS IS AN UNBALANCED TEXT');
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromWireFormat(): void{
        self::assertSame('',CharacterString::deserializeFromWireFormat("\x00")->getValue());
        self::assertSame('Text',CharacterString::deserializeFromWireFormat("\x04Text")->getValue());
        self::assertSame('Text with space',CharacterString::deserializeFromWireFormat("\x0FText with space")->getValue());
        self::assertSame('Text "with" quote',CharacterString::deserializeFromWireFormat("\x11Text \"with\" quote")->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromWireFormatNoData(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('A character string should have at least one octet of data to indicate the length.');

        CharacterString::deserializeFromWireFormat('');
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromWireFormatTooLessData(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('The character string length is higher than the available bytes.');

        CharacterString::deserializeFromWireFormat("\x04AB");
    }

}