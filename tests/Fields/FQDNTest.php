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
        self::assertInstanceOf(FQDN::class,new FQDN());
        self::assertInstanceOf(FQDN::class,new FQDN('@'));
        self::assertInstanceOf(FQDN::class,new FQDN('dotted.label'));
        self::assertInstanceOf(FQDN::class,new FQDN('@','dotted.label'));

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
        self::assertSame([],(new FQDN())->getValue());
        self::assertSame(['@'],(new FQDN('@'))->getValue());
        self::assertSame(['dotted.label'],(new FQDN('dotted.label'))->getValue());
        self::assertSame(['@','dotted.label'],(new FQDN('@','dotted.label'))->getValue());

        self::assertSame(['example','com'],(new FQDN('example','com'))->getValue());
        self::assertSame(['example','com',''],(new FQDN('example','com',''))->getValue());
        self::assertSame(['www','example','com'],(new FQDN('www','example','com'))->getValue());
        self::assertSame(['www','example','com',''],(new FQDN('www','example','com',''))->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testIsAbsolute(){
        self::assertFalse((new FQDN())->isAbsolute());
        self::assertFalse((new FQDN('@'))->isAbsolute());
        self::assertFalse((new FQDN('dotted.label'))->isAbsolute());
        self::assertFalse((new FQDN('@','dotted.label'))->isAbsolute());

        self::assertFalse((new FQDN('example','com'))->isAbsolute());
        self::assertTrue((new FQDN('example','com',''))->isAbsolute());
        self::assertFalse((new FQDN('www','example','com'))->isAbsolute());
        self::assertTrue((new FQDN('www','example','com',''))->isAbsolute());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testIsApex(){
        self::assertTrue((new FQDN())->isApex());
        self::assertFalse((new FQDN('@'))->isApex());

        self::assertFalse((new FQDN('example','com'))->isApex());
        self::assertFalse((new FQDN('@','example','com'))->isApex());

        self::assertFalse((new FQDN('example','com',''))->isApex());
        self::assertFalse((new FQDN('@','example','com',''))->isApex());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testIsRelative(){
        self::assertTrue((new FQDN())->isRelative());
        self::assertTrue((new FQDN('@'))->isRelative());
        self::assertTrue((new FQDN('dotted.label'))->isRelative());
        self::assertTrue((new FQDN('@','dotted.label'))->isRelative());

        self::assertTrue((new FQDN('example','com'))->isRelative());
        self::assertFalse((new FQDN('example','com',''))->isRelative());
        self::assertTrue((new FQDN('www','example','com'))->isRelative());
        self::assertFalse((new FQDN('www','example','com',''))->isRelative());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testMakeAbsolute(){
        $origin = new FQDN('example','com','');

        self::assertSame(['example','com','example','com',''],(new FQDN('example','com'))->makeAbsolute($origin,true)->getValue());
        self::assertSame(['example','com',''],(new FQDN('example','com',''))->makeAbsolute($origin,true)->getValue());
        self::assertSame(['www','example','com','example','com',''],(new FQDN('www','example','com'))->makeAbsolute($origin,true)->getValue());
        self::assertSame(['www','example','com',''],(new FQDN('www','example','com',''))->makeAbsolute($origin,true)->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testMakeRelative(){
        $origin = new FQDN('example','com','');

        self::assertSame(['example','com'],(new FQDN('example','com','example','com',''))->makeRelative($origin,true)->getValue());
        self::assertSame([],(new FQDN('example','com',''))->makeRelative($origin,true)->getValue());
        self::assertSame(['www','example','com'],(new FQDN('www','example','com','example','com',''))->makeRelative($origin,true)->getValue());
        self::assertSame(['www'],(new FQDN('www','example','com',''))->makeRelative($origin,true)->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testSerializeToPresentationFormat(): void{
        self::assertSame('@',(new FQDN())->serializeToPresentationFormat());
        self::assertSame('\@',(new FQDN('@'))->serializeToPresentationFormat());
        self::assertSame('dotted\.label',(new FQDN('dotted.label'))->serializeToPresentationFormat());
        self::assertSame('\@.dotted\.label',(new FQDN('@','dotted.label'))->serializeToPresentationFormat());

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
        self::assertSame("\x40",(new FQDN())->serializeToWireFormat());
        self::assertSame("\x01@\x40",(new FQDN('@'))->serializeToWireFormat());
        self::assertSame("\x0Cdotted.label\x40",(new FQDN('dotted.label'))->serializeToWireFormat());
        self::assertSame("\x01@\x0Cdotted.label\x40",(new FQDN('@','dotted.label'))->serializeToWireFormat());

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
        self::assertSame([],FQDN::deserializeFromPresentationFormat('@')->getValue());
        self::assertSame(['@'],FQDN::deserializeFromPresentationFormat('\@')->getValue());
        self::assertSame(['dotted.label'],FQDN::deserializeFromPresentationFormat('dotted\.label')->getValue());
        self::assertSame(['@','dotted.label'],FQDN::deserializeFromPresentationFormat('\@.dotted\.label')->getValue());

        self::assertSame(['example','com'],FQDN::deserializeFromPresentationFormat('example.com')->getValue());
        self::assertSame(['example','com',''],FQDN::deserializeFromPresentationFormat('example.com.')->getValue());
        self::assertSame(['www','example','com'],FQDN::deserializeFromPresentationFormat('www.example.com')->getValue());
        self::assertSame(['www','example','com',''],FQDN::deserializeFromPresentationFormat('www.example.com.')->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromPresentationFormatAtSign(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('At-sign cannot appear without backslash when having multiple labels.');

        FQDN::deserializeFromPresentationFormat('@.dotted\.label');
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromWireFormat(): void{
        self::assertSame([],FQDN::deserializeFromWireFormat("\x40")->getValue());
        self::assertSame(['@'],FQDN::deserializeFromWireFormat("\x01@\x40")->getValue());
        self::assertSame(['dotted.label'],FQDN::deserializeFromWireFormat("\x0Cdotted.label\x40")->getValue());
        self::assertSame(['@','dotted.label'],FQDN::deserializeFromWireFormat("\x01@\x0Cdotted.label\x40")->getValue());

        self::assertSame(['example','com'],FQDN::deserializeFromWireFormat("\x07example\x03com\x40")->getValue());
        self::assertSame(['example','com',''],FQDN::deserializeFromWireFormat("\x07example\x03com\x00")->getValue());
        self::assertSame(['www','example','com'],FQDN::deserializeFromWireFormat("\x03www\x07example\x03com\x40")->getValue());
        self::assertSame(['www','example','com',''],FQDN::deserializeFromWireFormat("\x03www\x07example\x03com\x00")->getValue());
    }

}