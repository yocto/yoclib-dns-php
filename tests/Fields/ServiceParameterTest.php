<?php
namespace YOCLIB\DNS\Tests\Fields;

use PHPUnit\Framework\TestCase;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Fields\ServiceParameter;

class ServiceParameterTest extends TestCase{

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testConstructor(): void{
        self::assertInstanceOf(ServiceParameter::class,new ServiceParameter(0,"ipv4hint,ipv6hint"));
        self::assertInstanceOf(ServiceParameter::class,new ServiceParameter(65280,"some value"));
    }

    /**
     * @return void
     */
    public function testConstructorCodeTooLarge(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Service parameter key should be between 0 and 65535.');

        new ServiceParameter(1234567,"ipv4hint,ipv6hint");
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testGetValue(): void{
        self::assertSame([0,"ipv4hint,ipv6hint"],(new ServiceParameter(0,"ipv4hint,ipv6hint"))->getValue());
        self::assertSame([65280,"some value"],(new ServiceParameter(65280,"some value"))->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testSerializeToPresentationFormat(): void{
        self::assertSame('mandatory="ipv4hint,ipv6hint"',(new ServiceParameter(0,"ipv4hint,ipv6hint"))->serializeToPresentationFormat());
        self::assertSame('key65280="some value"',(new ServiceParameter(65280,"some value"))->serializeToPresentationFormat());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testSerializeToWireFormat(): void{
        self::assertSame("\x00\x13\x00\x06\x02\x00\x78\x95\xA4\xE9",(new ServiceParameter(19,"\x02\x00\x78\x95\xA4\xE9"))->serializeToWireFormat());
    }

    /**
     * @return void
     */
    public function testCalculateLength(): void{
        self::assertSame(10,ServiceParameter::calculateLength("\x00\x13\x00\x06\x02\x00\x78\x95\xA4\xE9"));
        self::assertSame(10,ServiceParameter::calculateLength("\x00\x13\x00\x06\x02\x00\x78\x95\xA4\xE9trailingBytes"));
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromPresentationFormat(): void{
        self::assertSame([0,'ipv4hint,ipv6hint'],ServiceParameter::deserializeFromPresentationFormat('mandatory="ipv4hint,ipv6hint"')->getValue());
        self::assertSame([65280,'some value'],ServiceParameter::deserializeFromPresentationFormat('key65280="some value"')->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromWireFormat(): void{
        self::assertSame([19,"\x02\x00\x78\x95\xA4\xE9"],ServiceParameter::deserializeFromWireFormat("\x00\x13\x00\x06\x02\x00\x78\x95\xA4\xE9")->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromWireFormatTooLessData(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Service parameter should be at least 4 octets.');

        ServiceParameter::deserializeFromWireFormat("\xAA\xBB\xCC");
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromWireFormatTooLessServiceParameterData(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Too less data available to read service parameter.');

        ServiceParameter::deserializeFromWireFormat("\x00\x13\x00\x04\xAA\xBB");
    }

}