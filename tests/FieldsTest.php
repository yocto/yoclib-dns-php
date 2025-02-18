<?php
namespace YOCLIB\DNS\Tests;

use PHPUnit\Framework\TestCase;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Fields\CharacterString;
use YOCLIB\DNS\Fields\FQDN;
use YOCLIB\DNS\Fields\IPv4Address;
use YOCLIB\DNS\Fields\IPv6Address;
use YOCLIB\DNS\Fields\UnsignedInteger16;
use YOCLIB\DNS\Fields\UnsignedInteger8;

class FieldsTest extends TestCase{

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testGetValue(): void{
        self::assertEquals('1.2.3.4',(new IPv4Address('1.2.3.4'))->getValue());
        self::assertEquals('::',(new IPv6Address('::'))->getValue());
        self::assertEquals('This is text',(new CharacterString('This is text'))->getValue());
        self::assertEquals(123,(new UnsignedInteger8(123))->getValue());
        self::assertEquals(1234,(new UnsignedInteger16(1234))->getValue());
        self::assertEquals('example.com',(new FQDN('example.com'))->getValue());
        self::assertEquals('example.com.',(new FQDN('example.com.'))->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testAll(){
        self::assertEquals("\x01\x02\x03\x04",IPv4Address::deserializeFromPresentationFormat('1.2.3.4')->serializeToWireFormat());
        self::assertEquals("1.2.3.4",IPv4Address::deserializeFromWireFormat("\x01\x02\x03\x04")->serializeToPresentationFormat());

        self::assertEquals("\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",IPv6Address::deserializeFromPresentationFormat('::')->serializeToWireFormat());
        self::assertEquals("::",IPv6Address::deserializeFromWireFormat("\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00")->serializeToPresentationFormat());

        self::assertEquals("\x04Text",CharacterString::deserializeFromPresentationFormat('Text')->serializeToWireFormat());
        self::assertEquals("Text",CharacterString::deserializeFromWireFormat("\x04Text")->serializeToPresentationFormat());
        self::assertEquals("\x0FText with space",CharacterString::deserializeFromPresentationFormat('"Text with space"')->serializeToWireFormat());
        self::assertEquals('"Text with space"',CharacterString::deserializeFromWireFormat("\x0FText with space")->serializeToPresentationFormat());
        self::assertEquals("\x11Text \"with\" quote",CharacterString::deserializeFromPresentationFormat('"Text \"with\" quote"')->serializeToWireFormat());
        self::assertEquals('"Text \"with\" quote"',CharacterString::deserializeFromWireFormat("\x11Text \"with\" quote")->serializeToPresentationFormat());

        self::assertEquals("\x7F",UnsignedInteger8::deserializeFromPresentationFormat('127')->serializeToWireFormat());
        self::assertEquals("127",UnsignedInteger8::deserializeFromWireFormat("\x7F")->serializeToPresentationFormat());

        self::assertEquals("\x7F\x7F",UnsignedInteger16::deserializeFromPresentationFormat('32639')->serializeToWireFormat());
        self::assertEquals("32639",UnsignedInteger16::deserializeFromWireFormat("\x7F\x7F")->serializeToPresentationFormat());

        self::assertEquals("\x07example\x03com\x00",FQDN::deserializeFromPresentationFormat('example.com.')->serializeToWireFormat());
        self::assertEquals("example.com.",FQDN::deserializeFromWireFormat("\x07example\x03com\x00")->serializeToPresentationFormat());
        self::assertEquals("\x03www\x07example\x03com\x00",FQDN::deserializeFromPresentationFormat('www.example.com.')->serializeToWireFormat());
        self::assertEquals("www.example.com.",FQDN::deserializeFromWireFormat("\x03www\x07example\x03com\x00")->serializeToPresentationFormat());

        self::assertEquals("\x07example\x03com\x40",FQDN::deserializeFromPresentationFormat('example.com')->serializeToWireFormat());
        self::assertEquals("example.com",FQDN::deserializeFromWireFormat("\x07example\x03com\x40")->serializeToPresentationFormat());
        self::assertEquals("\x03www\x07example\x03com\x40",FQDN::deserializeFromPresentationFormat('www.example.com')->serializeToWireFormat());
        self::assertEquals("www.example.com",FQDN::deserializeFromWireFormat("\x03www\x07example\x03com\x40")->serializeToPresentationFormat());
    }

}