<?php
namespace YOCLIB\DNS\Tests\Fields;

use PHPUnit\Framework\TestCase;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Fields\FQDN;

class FQDNTest extends TestCase{

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testConstructor(): void{
        self::assertInstanceOf(FQDN::class,new FQDN('example','com'));
        self::assertInstanceOf(FQDN::class,new FQDN('example','com',''));
        self::assertInstanceOf(FQDN::class,new FQDN('www','example','com'));
        self::assertInstanceOf(FQDN::class,new FQDN('www','example','com',''));
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testTooLongLabel(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Label too long.');

        new FQDN('0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef','example','com','');
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testTooLongDomainName(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Domain name too long.');

        new FQDN('0123456789abcdef0123456789abcdef','0123456789abcdef0123456789abcdef','0123456789abcdef0123456789abcdef','0123456789abcdef0123456789abcdef','0123456789abcdef0123456789abcdef','0123456789abcdef0123456789abcdef','0123456789abcdef0123456789abcdef','0123456789abcdef0123456789abcdef','example','com','');
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testGetValue(): void{
        self::assertSame(['example','com'],(new FQDN('example','com'))->getValue());
        self::assertSame(['example','com',''],(new FQDN('example','com',''))->getValue());
        self::assertSame(['www','example','com'],(new FQDN('www','example','com'))->getValue());
        self::assertSame(['www','example','com',''],(new FQDN('www','example','com',''))->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testSerializeToPresentationFormat(): void{
        self::assertSame('example.com',(new FQDN('example','com'))->serializeToPresentationFormat());
        self::assertSame('example.com.',(new FQDN('example','com',''))->serializeToPresentationFormat());
        self::assertSame('www.example.com',(new FQDN('www','example','com'))->serializeToPresentationFormat());
        self::assertSame('www.example.com.',(new FQDN('www','example','com',''))->serializeToPresentationFormat());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testSerializeToWireFormat(): void{
        self::assertSame("\x07example\x03com\x40",(new FQDN('example','com'))->serializeToWireFormat());
        self::assertSame("\x07example\x03com\x00",(new FQDN('example','com',''))->serializeToWireFormat());
        self::assertSame("\x03www\x07example\x03com\x40",(new FQDN('www','example','com'))->serializeToWireFormat());
        self::assertSame("\x03www\x07example\x03com\x00",(new FQDN('www','example','com',''))->serializeToWireFormat());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromPresentationFormat(): void{
        self::assertSame(['example','com'],FQDN::deserializeFromPresentationFormat('example.com')->getValue());
        self::assertSame(['example','com',''],FQDN::deserializeFromPresentationFormat('example.com.')->getValue());
        self::assertSame(['www','example','com'],FQDN::deserializeFromPresentationFormat('www.example.com')->getValue());
        self::assertSame(['www','example','com',''],FQDN::deserializeFromPresentationFormat('www.example.com.')->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromWireFormat(): void{
        self::assertSame(['example','com'],FQDN::deserializeFromWireFormat("\x07example\x03com\x40")->getValue());
        self::assertSame(['example','com',''],FQDN::deserializeFromWireFormat("\x07example\x03com\x00")->getValue());
        self::assertSame(['www','example','com'],FQDN::deserializeFromWireFormat("\x03www\x07example\x03com\x40")->getValue());
        self::assertSame(['www','example','com',''],FQDN::deserializeFromWireFormat("\x03www\x07example\x03com\x00")->getValue());
    }

}