<?php
namespace YOCLIB\DNS\Tests\Fields;

use PHPUnit\Framework\TestCase;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Fields\AddressPrefix;

class AddressPrefixTest extends TestCase{

    /**
     * @return void
     */
    public function testConstructor(): void{
        self::assertInstanceOf(AddressPrefix::class,new AddressPrefix('1','21',false,'192.168.32.0'));
        self::assertInstanceOf(AddressPrefix::class,new AddressPrefix('1','28',true,'192.168.38.0'));
        self::assertInstanceOf(AddressPrefix::class,new AddressPrefix('2','8',false,'FF00:0:0:0:0:0:0:0'));
    }

    /**
     * @return void
     */
    public function testConstructorTooLessArguments(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('The value should have 4 elements.');

        new AddressPrefix('1','21',false);
    }

    /**
     * @return void
     */
    public function testConstructorInvalidAddressFamily(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Only IPv4 and IPv6 address families are supported.');

        new AddressPrefix('3','21',false,'example.com.');
    }

    /**
     * @return void
     */
    public function testConstructorNegativePrefix(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Prefix should be positive.');

        new AddressPrefix('1','-5',false,'192.168.32.0');
    }

    /**
     * @return void
     */
    public function testConstructorTooLargePrefixIPv4(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Prefix cannot be larger than 32 for IPv4 addresses.');

        new AddressPrefix('1','40',false,'192.168.32.0');
    }

    /**
     * @return void
     */
    public function testConstructorTooLargePrefixIPv6(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Prefix cannot be larger than 128 for IPv6 addresses.');

        new AddressPrefix('2','150',false,'FF00:0:0:0:0:0:0:0');
    }

    /**
     * @return void
     */
    public function testConstructorInvalidIPv4(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Human readable IPv4 address should have 4 unsigned integers ranging from 0 to 255, all seperated by dot.');

        new AddressPrefix('1','21',false,'192_168.32.0');
    }

    /**
     * @return void
     */
    public function testConstructorInvalidIPv6(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Human readable IPv6 address isn\'t valid.');

        new AddressPrefix('2','8',false,'FF00_0:0:0:0:0:0:0');
    }

    /**
     * @return void
     */
    public function testGetValue(): void{
        self::assertSame(['1','21',false,'192.168.32.0'],(new AddressPrefix('1','21',false,'192.168.32.0'))->getValue());
        self::assertSame(['1','28',true,'192.168.38.0'],(new AddressPrefix('1','28',true,'192.168.38.0'))->getValue());
        self::assertSame(['2','8',false,'FF00:0:0:0:0:0:0:0'],(new AddressPrefix('2','8',false,'FF00:0:0:0:0:0:0:0'))->getValue());
    }

    /**
     * @return void
     */
    public function testSerializeToPresentationFormat(): void{
        self::assertSame('1:192.168.32.0/21',(new AddressPrefix('1','21',false,'192.168.32.0'))->serializeToPresentationFormat());
        self::assertSame('!1:192.168.38.0/28',(new AddressPrefix('1','28',true,'192.168.38.0'))->serializeToPresentationFormat());
        self::assertSame('2:FF00:0:0:0:0:0:0:0/8',(new AddressPrefix('2','8',false,'FF00:0:0:0:0:0:0:0'))->serializeToPresentationFormat());
    }

    /**
     * @return void
     */
    public function testSerializeToWireFormat(): void{
        self::assertSame("\x00\x01\x15\x03\xC0\xA8\x20",(new AddressPrefix('1','21',false,'192.168.32.0'))->serializeToWireFormat());
        self::assertSame("\x00\x01\x1C\x83\xC0\xA8\x26",(new AddressPrefix('1','28',true,'192.168.38.0'))->serializeToWireFormat());
        self::assertSame("\x00\x02\x08\x01\xFF",(new AddressPrefix('2','8',false,'FF00:0:0:0:0:0:0:0'))->serializeToWireFormat());
    }

    /**
     * @return void
     */
    public function testCalculateLength(): void{
        self::assertSame(7,AddressPrefix::calculateLength("\x00\x01\x15\x03\xC0\xA8\x20"));
        self::assertSame(7,AddressPrefix::calculateLength("\x00\x01\x15\x03\xC0\xA8\x20trailingBytes"));
        self::assertSame(7,AddressPrefix::calculateLength("\x00\x01\x1C\x83\xC0\xA8\x26"));
        self::assertSame(7,AddressPrefix::calculateLength("\x00\x01\x1C\x83\xC0\xA8\x26trailingBytes"));
        self::assertSame(5,AddressPrefix::calculateLength("\x00\x02\x08\x01\xFF"));
        self::assertSame(5,AddressPrefix::calculateLength("\x00\x02\x08\x01\xFFtrailingBytes"));
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromPresentationFormat(): void{
        self::assertSame(['1','21',false,'192.168.32.0'],AddressPrefix::deserializeFromPresentationFormat('1:192.168.32.0/21')->getValue());
        self::assertSame(['1','28',true,'192.168.38.0'],AddressPrefix::deserializeFromPresentationFormat('!1:192.168.38.0/28')->getValue());
        self::assertSame(['2','8',false,'FF00:0:0:0:0:0:0:0'],AddressPrefix::deserializeFromPresentationFormat('2:FF00:0:0:0:0:0:0:0/8')->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromPresentationFormatAtSign(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Invalid address prefix format.');

        AddressPrefix::deserializeFromPresentationFormat('!!1:192.168.32.0/21');
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromWireFormat(): void{
        self::assertSame(['1','21',false,'192.168.32.0'],AddressPrefix::deserializeFromWireFormat("\x00\x01\x15\x03\xC0\xA8\x20")->getValue());
        self::assertSame(['1','28',true,'192.168.38.0'],AddressPrefix::deserializeFromWireFormat("\x00\x01\x1C\x83\xC0\xA8\x26")->getValue());
        self::assertSame(['2','8',false,'ff00::'],AddressPrefix::deserializeFromWireFormat("\x00\x02\x08\x01\xFF")->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromWireFormatTooLessData(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Address Prefix should be at least 4 octets.');

        AddressPrefix::deserializeFromWireFormat("\xAA\xBB\xCC");
    }

}