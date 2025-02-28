<?php
namespace YOCLIB\DNS\Tests\Fields;

use PHPUnit\Framework\TestCase;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Fields\IPv6Address;

class IPv6AddressTest extends TestCase{

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testConstructor(): void{
        self::assertInstanceOf(IPv6Address::class,new IPv6Address('::'));
        self::assertInstanceOf(IPv6Address::class,new IPv6Address('::1'));
        self::assertInstanceOf(IPv6Address::class,new IPv6Address('fe80::'));
        self::assertInstanceOf(IPv6Address::class,new IPv6Address('fe80::1'));
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testNotIPv4(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Human readable IPv6 address isn\'t valid.');

        new IPv6Address('0.0.0.0');
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testGetValue(): void{
        self::assertSame('::',(new IPv6Address('::'))->getValue());
        self::assertSame('::1',(new IPv6Address('::1'))->getValue());
        self::assertSame('fe80::',(new IPv6Address('fe80::'))->getValue());
        self::assertSame('fe80::1',(new IPv6Address('fe80::1'))->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testSerializeToPresentationFormat(): void{
        self::assertSame('::',(new IPv6Address('::'))->serializeToPresentationFormat());
        self::assertSame('::1',(new IPv6Address('::1'))->serializeToPresentationFormat());
        self::assertSame('fe80::',(new IPv6Address('fe80::'))->serializeToPresentationFormat());
        self::assertSame('fe80::1',(new IPv6Address('fe80::1'))->serializeToPresentationFormat());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testSerializeToWireFormat(): void{
        self::assertSame("\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",(new IPv6Address('::'))->serializeToWireFormat());
        self::assertSame("\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x01",(new IPv6Address('::1'))->serializeToWireFormat());
        self::assertSame("\xFE\x80\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",(new IPv6Address('fe80::'))->serializeToWireFormat());
        self::assertSame("\xFE\x80\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x01",(new IPv6Address('fe80::1'))->serializeToWireFormat());
    }

    /**
     * @return void
     */
    public function testCalculateLength(): void{
        self::assertSame(16,IPv6Address::calculateLength("\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00"));
        self::assertSame(16,IPv6Address::calculateLength("\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00trailingBytes"));
        self::assertSame(16,IPv6Address::calculateLength("\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x01"));
        self::assertSame(16,IPv6Address::calculateLength("\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x01trailingBytes"));
        self::assertSame(16,IPv6Address::calculateLength("\xFE\x80\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00"));
        self::assertSame(16,IPv6Address::calculateLength("\xFE\x80\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00trailingBytes"));
        self::assertSame(16,IPv6Address::calculateLength("\xFE\x80\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x01"));
        self::assertSame(16,IPv6Address::calculateLength("\xFE\x80\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x01trailingBytes"));
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromPresentationFormat(): void{
        self::assertSame('::',IPv6Address::deserializeFromPresentationFormat('::')->getValue());
        self::assertSame('::1',IPv6Address::deserializeFromPresentationFormat('::1')->getValue());
        self::assertSame('fe80::',IPv6Address::deserializeFromPresentationFormat('fe80::')->getValue());
        self::assertSame('fe80::1',IPv6Address::deserializeFromPresentationFormat('fe80::1')->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromWireFormat(): void{
        self::assertSame('::',IPv6Address::deserializeFromWireFormat("\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00")->getValue());
        self::assertSame('::1',IPv6Address::deserializeFromWireFormat("\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x01")->getValue());
        self::assertSame('fe80::',IPv6Address::deserializeFromWireFormat("\xFE\x80\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00")->getValue());
        self::assertSame('fe80::1',IPv6Address::deserializeFromWireFormat("\xFE\x80\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x01")->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromWireFormatTooLessData(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Binary IPv6 address should be 16 octets.');

        IPv6Address::deserializeFromWireFormat("\xFE\x80\x00\x00\x00\x00\x00\x00\x00");
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromWireFormatTooMuchData(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Binary IPv6 address should be 16 octets.');

        IPv6Address::deserializeFromWireFormat("\xFE\x80\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x01\x01\x01\x01\x01\x01\x01\x01\x01");
    }

}