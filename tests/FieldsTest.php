<?php
namespace YOCLIB\DNS\Tests;

use PHPUnit\Framework\TestCase;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Fields\Bitmap;
use YOCLIB\DNS\Fields\CharacterString;
use YOCLIB\DNS\Fields\FQDN;
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
        self::assertSame([1,2,3,4,5,6],(new Bitmap([1,2,3,4,5,6]))->getValue());
        self::assertSame('This is text',(new CharacterString('This is text'))->getValue());
        self::assertSame(['example','com'],(new FQDN('example','com'))->getValue());
        self::assertSame(['example','com',''],(new FQDN('example','com',''))->getValue());
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
        self::assertSame('1 2 3 4 5 6',(new Bitmap([1,2,3,4,5,6]))->serializeToPresentationFormat());
        self::assertSame('"This is text"',(new CharacterString('This is text'))->serializeToPresentationFormat());
        self::assertSame("example.com",(new FQDN('example','com'))->serializeToPresentationFormat());
        self::assertSame("example.com.",(new FQDN('example','com',''))->serializeToPresentationFormat());
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
        self::assertSame(chr(0b1111110),(new Bitmap([1,2,3,4,5,6]))->serializeToWireFormat());
        self::assertSame("\x0CThis is text",(new CharacterString('This is text'))->serializeToWireFormat());
        self::assertSame("\x07example\x03com\x40",(new FQDN('example','com'))->serializeToWireFormat());
        self::assertSame("\x07example\x03com\x00",(new FQDN('example','com',''))->serializeToWireFormat());
        self::assertSame("\x01\x02\x03\x04",(new IPv4Address('1.2.3.4'))->serializeToWireFormat());
        self::assertSame("\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",(new IPv6Address('::'))->serializeToWireFormat());
        self::assertSame(chr(123),(new UnsignedInteger8(123))->serializeToWireFormat());
        self::assertSame(pack('n',1234),(new UnsignedInteger16(1234))->serializeToWireFormat());
        self::assertSame(pack('N',12345678),(new UnsignedInteger32(12345678))->serializeToWireFormat());
    }

    public function testBitmapDuplicateBits(){
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('No duplicate bits allowed.');

        new Bitmap([1,2,3,3,4,5,6]);
    }

    public function testBitmapNonIntegerBits(){
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Only integers allowed.');

        new Bitmap([1,2,'3',4,5,6]);
    }

    public function testBitmapNegativeIntegers(){
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Only positive integers allowed.');

        new Bitmap([1,2,-3,4,5,6]);
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testAll(): void{
        $mapping = [
            1 => 'A',
            2 => 'NS',
            3 => 'MD',
            4 => 'MF',
        ];
        self::assertEquals(chr((1<<1) | (1<<3)),Bitmap::deserializeFromPresentationFormat('A MD',$mapping)->serializeToWireFormat());
        self::assertEquals('A MD',Bitmap::deserializeFromWireFormat(chr((1<<1) | (1<<3)))->serializeToPresentationFormat($mapping));

        self::assertEquals("\x04Text",CharacterString::deserializeFromPresentationFormat('Text')->serializeToWireFormat());
        self::assertEquals("Text",CharacterString::deserializeFromWireFormat("\x04Text")->serializeToPresentationFormat());
        self::assertEquals("\x0FText with space",CharacterString::deserializeFromPresentationFormat('"Text with space"')->serializeToWireFormat());
        self::assertEquals('"Text with space"',CharacterString::deserializeFromWireFormat("\x0FText with space")->serializeToPresentationFormat());
        self::assertEquals("\x11Text \"with\" quote",CharacterString::deserializeFromPresentationFormat('"Text \"with\" quote"')->serializeToWireFormat());
        self::assertEquals('"Text \"with\" quote"',CharacterString::deserializeFromWireFormat("\x11Text \"with\" quote")->serializeToPresentationFormat());

        self::assertEquals("\x07example\x03com\x00",FQDN::deserializeFromPresentationFormat('example.com.')->serializeToWireFormat());
        self::assertEquals("example.com.",FQDN::deserializeFromWireFormat("\x07example\x03com\x00")->serializeToPresentationFormat());
        self::assertEquals("\x03www\x07example\x03com\x00",FQDN::deserializeFromPresentationFormat('www.example.com.')->serializeToWireFormat());
        self::assertEquals("www.example.com.",FQDN::deserializeFromWireFormat("\x03www\x07example\x03com\x00")->serializeToPresentationFormat());

        self::assertEquals("\x07example\x03com\x40",FQDN::deserializeFromPresentationFormat('example.com')->serializeToWireFormat());
        self::assertEquals("example.com",FQDN::deserializeFromWireFormat("\x07example\x03com\x40")->serializeToPresentationFormat());
        self::assertEquals("\x03www\x07example\x03com\x40",FQDN::deserializeFromPresentationFormat('www.example.com')->serializeToWireFormat());
        self::assertEquals("www.example.com",FQDN::deserializeFromWireFormat("\x03www\x07example\x03com\x40")->serializeToPresentationFormat());

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