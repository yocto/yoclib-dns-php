<?php
namespace YOCLIB\DNS\Tests\Fields;

use PHPUnit\Framework\TestCase;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Fields\IPv4Address;

class IPv4AddressTest extends TestCase{

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testConstructor(): void{
        self::assertInstanceOf(IPv4Address::class,new IPv4Address('10.0.1.1'));
        self::assertInstanceOf(IPv4Address::class,new IPv4Address('127.0.2.1'));
        self::assertInstanceOf(IPv4Address::class,new IPv4Address('172.20.3.1'));
        self::assertInstanceOf(IPv4Address::class,new IPv4Address('192.168.4.1'));
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testNotIPv4(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Human readable IPv4 address should have 4 unsigned integers ranging from 0 to 255, all seperated by dot.');

        new IPv4Address('::');
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testGetValue(): void{
        self::assertSame('10.0.1.1',(new IPv4Address('10.0.1.1'))->getValue());
        self::assertSame('127.0.2.1',(new IPv4Address('127.0.2.1'))->getValue());
        self::assertSame('172.20.3.1',(new IPv4Address('172.20.3.1'))->getValue());
        self::assertSame('192.168.4.1',(new IPv4Address('192.168.4.1'))->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testSerializeToPresentationFormat(): void{
        self::assertSame('10.0.1.1',(new IPv4Address('10.0.1.1'))->serializeToPresentationFormat());
        self::assertSame('127.0.2.1',(new IPv4Address('127.0.2.1'))->serializeToPresentationFormat());
        self::assertSame('172.20.3.1',(new IPv4Address('172.20.3.1'))->serializeToPresentationFormat());
        self::assertSame('192.168.4.1',(new IPv4Address('192.168.4.1'))->serializeToPresentationFormat());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testSerializeToWireFormat(): void{
        self::assertSame("\x0A\x00\x01\x01",(new IPv4Address('10.0.1.1'))->serializeToWireFormat());
        self::assertSame("\x7F\x00\x02\x01",(new IPv4Address('127.0.2.1'))->serializeToWireFormat());
        self::assertSame("\xAC\x14\x03\x01",(new IPv4Address('172.20.3.1'))->serializeToWireFormat());
        self::assertSame("\xC0\xA8\x04\x01",(new IPv4Address('192.168.4.1'))->serializeToWireFormat());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromPresentationFormat(): void{
        self::assertSame('10.0.1.1',IPv4Address::deserializeFromPresentationFormat('10.0.1.1')->getValue());
        self::assertSame('127.0.2.1',IPv4Address::deserializeFromPresentationFormat('127.0.2.1')->getValue());
        self::assertSame('172.20.3.1',IPv4Address::deserializeFromPresentationFormat('172.20.3.1')->getValue());
        self::assertSame('192.168.4.1',IPv4Address::deserializeFromPresentationFormat('192.168.4.1')->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromWireFormat(): void{
        self::assertSame('10.0.1.1',IPv4Address::deserializeFromWireFormat("\x0A\x00\x01\x01")->getValue());
        self::assertSame('127.0.2.1',IPv4Address::deserializeFromWireFormat("\x7F\x00\x02\x01")->getValue());
        self::assertSame('172.20.3.1',IPv4Address::deserializeFromWireFormat("\xAC\x14\x03\x01")->getValue());
        self::assertSame('192.168.4.1',IPv4Address::deserializeFromWireFormat("\xC0\xA8\x04\x01")->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromWireFormatTooLessData(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Binary IPv4 address should be 4 octets.');

        IPv4Address::deserializeFromWireFormat("\xAA\xBB\xCC");
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromWireFormatTooMuchData(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Binary IPv4 address should be 4 octets.');

        IPv4Address::deserializeFromWireFormat("\xAA\xBB\xCC\xDD\xEE");
    }

}